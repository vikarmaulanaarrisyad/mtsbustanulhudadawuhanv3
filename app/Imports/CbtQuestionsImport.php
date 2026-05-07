<?php

namespace App\Imports;

use App\Models\CbtBank;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

use Maatwebsite\Excel\Concerns\WithChunkReading;
class CbtQuestionsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithEvents, WithChunkReading
{
    public function chunkSize(): int
    {
        return 50; // Process in larger chunks to reduce overhead
    }
    protected CbtBank $bank;
    protected array $uploadedImages;
    protected int $importedCount = 0;
    protected int $currentRow = 1; // Track current row globally across chunks
    protected array $errors = [];
    protected array $summary = [];
    protected $sheet;
    protected $drawings = [];
    protected bool $drawingsLoaded = false;

    public function __construct(CbtBank $bank, array $uploadedImages = [])
    {
        $this->bank = $bank;
        $this->uploadedImages = $uploadedImages;
    }

    /**
     * Map Indonesian/shorthand type to database enum value.
     */
    protected function mapQuestionType(string $type): ?string
    {
        $map = [
            'pg'            => 'pilihan_ganda',
            'pilihan ganda' => 'pilihan_ganda',
            'pilihan_ganda' => 'pilihan_ganda',
            'pgk'           => 'ganda_komplek',
            'ganda komplek' => 'ganda_komplek',
            'ganda_komplek' => 'ganda_komplek',
            'penjodohan'    => 'penjodohan',
            'matching'      => 'penjodohan',
            'jodohkan'      => 'penjodohan',
            'essay'         => 'essay',
            'esay'          => 'essay',
            'isian'         => 'essay',
            'uraian'        => 'uraian',
        ];

        return $map[strtolower(trim($type))] ?? null;
    }

    /**
     * Extract image reference from question text [IMG:filename.jpg]
     */
    protected function extractImageReference(string &$text): ?string
    {
        if (preg_match('/\[IMG:([^\]]+)\]/', $text, $matches)) {
            $text = trim(preg_replace('/\[IMG:[^\]]+\]/', '', $text));
            return $matches[1];
        }
        return null;
    }

    /**
     * Save uploaded image and return its storage path.
     */
    protected function saveImage(string $filename): ?string
    {
        if (isset($this->uploadedImages[$filename])) {
            $file = $this->uploadedImages[$filename];
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $storagePath = 'cbt/images/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($storagePath, file_get_contents($file->getRealPath()));
            
            // Remove from memory to save space
            unset($this->uploadedImages[$filename]);
            
            return $storagePath;
        }
        return null;
    }

    /**
     * Find image from uploaded images by potential filenames.
     */
    protected function findUploadedImage(array $names): ?string
    {
        foreach ($names as $name) {
            if (empty($name)) continue;
            
            // 1. Try exact match from array key
            if (isset($this->uploadedImages[$name])) {
                return $this->saveImage($name);
            }
            
            // 2. Try case-insensitive search if not found
            foreach ($this->uploadedImages as $originalName => $file) {
                if (strtolower($originalName) === strtolower($name)) {
                    return $this->saveImage($originalName);
                }
                
                // 3. Try without extension
                $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                if (strtolower($nameWithoutExt) === strtolower($name)) {
                    return $this->saveImage($originalName);
                }
            }
        }
        return null;
    }

    /**
     * Resolve image from multiple sources: embedded, filename in cell, or naming convention.
     */
    protected function resolveImage($col, $rowNumber, $excelValue, $conventionNames = []): ?string
    {
        // 1. Check if the cell itself contains a filename string
        if (!empty($excelValue) && is_string($excelValue) && !is_numeric($excelValue)) {
            $found = $this->findUploadedImage([trim($excelValue)]);
            if ($found) return $found;
        }

        // 2. Check for naming convention (e.g. "1.jpg" or "row_2_A")
        if (!empty($conventionNames)) {
            $found = $this->findUploadedImage($conventionNames);
            if ($found) return $found;
        }

        // 3. Check for embedded drawing in the cell
        $drawing = $this->getDrawingFromCell($col . $rowNumber);
        if ($drawing) return $drawing;

        // FALLBACK: If nothing found but we have a filename in Excel, save it as a placeholder
        if (!empty($excelValue) && is_string($excelValue) && !is_numeric($excelValue)) {
            return trim($excelValue);
        }

        return null;
    }

    /**
     * Get drawing from a specific cell.
     */
    protected function getDrawingFromCell(string $coordinate): ?string
    {
        // Try exact match first
        if (isset($this->drawings[$coordinate])) {
            $drawing = $this->drawings[$coordinate];
            unset($this->drawings[$coordinate]); // Clear from memory once used
            return $this->processDrawing($drawing);
        }

        // Fallback: search for drawings that might be anchored to a range (e.g. "D4:E6")
        foreach ($this->drawings as $coord => $drawing) {
            if (strpos($coord, ':') !== false) {
                $parts = explode(':', $coord);
                if ($parts[0] === $coordinate) {
                    unset($this->drawings[$coord]);
                    return $this->processDrawing($drawing);
                }
            }
        }
        
        return null;
    }

    /**
     * Helper to process and save drawing
     */
    protected function processDrawing($drawing): ?string
    {
        try {
            if ($drawing instanceof Drawing) {
                $path = $drawing->getPath();
                if (!file_exists($path)) return null;
                
                $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
                $filename = Str::uuid() . '.' . $extension;
                $storagePath = 'cbt/images/' . $filename;
                
                Storage::disk('public')->put($storagePath, file_get_contents($path));
                return $storagePath;
            } elseif ($drawing instanceof MemoryDrawing) {
                ob_start();
                call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                $imageContents = ob_get_contents();
                ob_end_clean();
                
                $extension = 'png';
                $filename = Str::uuid() . '.' . $extension;
                $storagePath = 'cbt/images/' . $filename;
                
                Storage::disk('public')->put($storagePath, $imageContents);
                return $storagePath;
            }
        } catch (\Exception $e) {
            \Log::error("Error processing Excel drawing: " . $e->getMessage());
        }
        return null;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->sheet = $event->sheet->getDelegate();
                
                // IMPORTANT: When chunking, BeforeSheet is called for every chunk.
                // We only want to load drawings once to save memory and time.
                if (!$this->drawingsLoaded) {
                    $this->drawings = [];
                    foreach ($this->sheet->getDrawingCollection() as $drawing) {
                        $coordinate = $drawing->getCoordinates();
                        $this->drawings[$coordinate] = $drawing;
                    }
                    $this->drawingsLoaded = true;
                }
            },
        ];
    }

    public function collection(Collection $rows)
    {
        $typeCounts = [
            'pilihan_ganda' => 0,
            'ganda_komplek' => 0,
            'penjodohan'    => 0,
            'essay'         => 0,
            'uraian'        => 0,
        ];

        DB::beginTransaction();

        try {
            \Log::info('Importing ' . $rows->count() . ' rows starting from row ' . ($this->currentRow + 1));
            foreach ($rows as $index => $row) {
                $this->currentRow++;
                $rowNumber = $this->currentRow;
                
                if ($index % 10 == 0) {
                    \Log::info("Processing row {$rowNumber}...");
                    gc_collect_cycles(); // Force cleanup
                }

                // Skip completely empty rows
                $soal = trim($row['soal'] ?? '');
                $tipe = trim($row['tipe_soal'] ?? '');
                $gambar = trim($row['gambar_soal'] ?? '');

                if (empty($soal) && empty($tipe) && empty($gambar)) {
                    continue;
                }

                if (empty($tipe)) {
                    $this->errors[] = "Baris {$rowNumber}: Tipe soal wajib diisi.";
                    continue;
                }

                // Validate question type
                $questionType = $this->mapQuestionType($tipe);
                if (!$questionType) {
                    $this->errors[] = "Baris {$rowNumber}: Tipe soal '{$tipe}' tidak valid. Gunakan: PG, PGK, Penjodohan, Essay, Uraian.";
                    continue;
                }

                // Resolve Question Image
                $no = trim($row['no'] ?? '');
                $conventionNames = [];
                if (!empty($no)) $conventionNames[] = $no;
                $conventionNames[] = "soal_" . $rowNumber;
                $conventionNames[] = (string) $rowNumber;

                $imagePath = $this->resolveImage('D', $rowNumber, $row['gambar_soal'] ?? null, $conventionNames);
                
                // Fallback to manual upload reference in text [IMG:...]
                if (!$imagePath) {
                    $questionText = $soal;
                    $imageRef = $this->extractImageReference($questionText);
                    if ($imageRef) {
                        $imagePath = $this->saveImage($imageRef);
                        $soal = $questionText;
                    }
                }

                // Get score weight
                $scoreWeight = !empty($row['bobot_nilai']) ? (int) $row['bobot_nilai'] : 1;

                // Build question data
                $questionData = [
                    'cbt_bank_id'    => $this->bank->id,
                    'no'             => $no,
                    'question_text'  => $soal,
                    'question_type'  => $questionType,
                    'question_image' => $imagePath,
                    'score_weight'   => $scoreWeight,
                ];

                // Handle matching pairs for penjodohan
                if ($questionType === 'penjodohan') {
                    $pairsRaw = trim($row['pasangan_penjodohan'] ?? '');
                    $matchingPairs = [];

                    if (!empty($pairsRaw)) {
                        // Check if it's JSON
                        if (Str::startsWith($pairsRaw, '{')) {
                            $decoded = json_decode($pairsRaw, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $matchingPairs = $decoded;
                            }
                        } else {
                            // Fallback to Premis=Respon|Premis=Respon
                            $pairs = explode('|', $pairsRaw);
                            foreach ($pairs as $pair) {
                                $parts = explode('=', $pair, 2);
                                if (count($parts) === 2) {
                                    $premise = trim($parts[0]);
                                    $response = trim($parts[1]);

                                    // Resolve images in matching pairs
                                    // We allow direct filename reference if it looks like an image
                                    $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    
                                    // Resolve Premise Image
                                    $premiseExt = strtolower(pathinfo($premise, PATHINFO_EXTENSION));
                                    if (in_array($premiseExt, $imgExts)) {
                                        $resolvedPremise = $this->resolveImage(null, $rowNumber, $premise);
                                        if ($resolvedPremise) $premise = "[IMG]{$resolvedPremise}[/IMG]";
                                    }

                                    // Resolve Response Image
                                    $responseExt = strtolower(pathinfo($response, PATHINFO_EXTENSION));
                                    if (in_array($responseExt, $imgExts)) {
                                        $resolvedResponse = $this->resolveImage(null, $rowNumber, $response);
                                        if ($resolvedResponse) $response = "[IMG]{$resolvedResponse}[/IMG]";
                                    }

                                    $matchingPairs[$premise] = $response;
                                }
                            }
                        }
                    }

                    if (empty($matchingPairs)) {
                        $this->errors[] = "Baris {$rowNumber}: Soal penjodohan harus memiliki pasangan. Format JSON atau Premis1=Respon1|Premis2=Respon2";
                        continue;
                    }

                    $questionData['matching_pairs'] = $matchingPairs;
                }

                // Handle answer key for essay
                if (in_array($questionType, ['essay', 'uraian'])) {
                    $questionData['answer_key'] = trim($row['jawaban_benar'] ?? '') ?: null;
                }

                // Create question
                $question = CbtQuestion::create($questionData);

                // Create options for PG and PGK
                if (in_array($questionType, ['pilihan_ganda', 'ganda_komplek'])) {
                    // Mapping columns: Opsi A(E), Gambar A(F), Opsi B(G), Gambar B(H), Opsi C(I), Gambar C(J), Opsi D(K), Gambar D(L), Opsi E(M), Gambar E(N)
                    $optionConfigs = [
                        'A' => ['text' => 'opsi_a', 'img_col' => 'F', 'img_text' => 'gambar_opsi_a'],
                        'B' => ['text' => 'opsi_b', 'img_col' => 'H', 'img_text' => 'gambar_opsi_b'],
                        'C' => ['text' => 'opsi_c', 'img_col' => 'J', 'img_text' => 'gambar_opsi_c'],
                        'D' => ['text' => 'opsi_d', 'img_col' => 'L', 'img_text' => 'gambar_opsi_d'],
                        'E' => ['text' => 'opsi_e', 'img_col' => 'N', 'img_text' => 'gambar_opsi_e'],
                    ];
                    
                    $correctAnswers = array_map('trim', explode(',', strtoupper(trim($row['jawaban_benar'] ?? 'A'))));
                    $hasAnyOption = false;

                    foreach ($optionConfigs as $letter => $cfg) {
                        $optionText = trim($row[$cfg['text']] ?? '');
                        
                        // Resolve Option Image
                        $optConventionNames = [];
                        if (!empty($no)) $optConventionNames[] = "{$no}_{$letter}";
                        $optConventionNames[] = "{$rowNumber}_{$letter}";
                        $optConventionNames[] = "soal_{$rowNumber}_{$letter}";

                        $optImagePath = $this->resolveImage($cfg['img_col'], $rowNumber, $row[$cfg['img_text'] ?? ''] ?? null, $optConventionNames);
                        
                        if (empty($optionText) && !$optImagePath) {
                            continue;
                        }

                        $hasAnyOption = true;

                        // Fallback to manual upload reference in option text
                        if (!$optImagePath && !empty($optionText)) {
                            $tempText = $optionText;
                            $optImageRef = $this->extractImageReference($tempText);
                            if ($optImageRef) {
                                $optImagePath = $this->saveImage($optImageRef);
                                $optionText = $tempText;
                            }
                        }

                        CbtOption::create([
                            'cbt_question_id' => $question->id,
                            'option_text'     => $optionText,
                            'option_image'    => $optImagePath,
                            'is_correct'      => in_array($letter, $correctAnswers),
                        ]);
                    }

                    if (!$hasAnyOption) {
                        $this->errors[] = "Baris {$rowNumber}: Soal PG/PGK harus memiliki minimal 2 opsi jawaban.";
                        $question->delete();
                        continue;
                    }
                }

                $typeCounts[$questionType]++;
                $this->importedCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errors[] = "Terjadi kesalahan sistem di baris " . ($rowNumber ?? 'unknown') . ": " . $e->getMessage();
        }

        $this->summary = $typeCounts;
    }


    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSummary(): array
    {
        return $this->summary;
    }
}
