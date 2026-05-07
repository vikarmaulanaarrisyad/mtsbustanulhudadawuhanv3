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

class CbtQuestionsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithEvents
{
    protected CbtBank $bank;
    protected array $uploadedImages;
    protected int $importedCount = 0;
    protected array $errors = [];
    protected array $summary = [];
    protected $sheet;
    protected $drawings = [];

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
            $storagePath = 'cbt/images/' . Str::uuid() . '_' . $filename;
            Storage::disk('public')->put($storagePath, file_get_contents($file->getRealPath()));
            return $storagePath;
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
            return $this->processDrawing($this->drawings[$coordinate]);
        }

        // Fallback: search for drawings that intersect with this cell
        foreach ($this->drawings as $coord => $drawing) {
            if (strpos($coord, $coordinate) !== false) {
                return $this->processDrawing($drawing);
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
                $this->drawings = [];
                
                // Get all drawings from the sheet
                foreach ($this->sheet->getDrawingCollection() as $drawing) {
                    $coordinate = $drawing->getCoordinates();
                    // Some drawings might be anchored to a range, e.g. "D4:E6"
                    // We store the primary coordinate
                    $this->drawings[$coordinate] = $drawing;
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
            \Log::info('Importing ' . $rows->count() . ' rows.');
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; 
                
                if ($index % 5 == 0) {
                    \Log::info("Processing row {$rowNumber}...");
                }

                // Skip completely empty rows
                $soal = trim($row['soal'] ?? '');
                $tipe = trim($row['tipe_soal'] ?? '');

                if (empty($soal) && empty($tipe)) {
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

                // Get image from cell (Gambar Soal is in column D)
                $imagePath = $this->getDrawingFromCell('D' . $rowNumber);
                
                // Fallback to manual upload reference in text if no embedded image
                if (!$imagePath) {
                    $questionText = $soal;
                    $imageRef = $this->extractImageReference($questionText);
                    if ($imageRef) {
                        $imagePath = $this->saveImage($imageRef);
                        $soal = $questionText; // Update text after extraction
                    }
                }

                // Get score weight
                $scoreWeight = !empty($row['bobot_nilai']) ? (int) $row['bobot_nilai'] : 1;

                // Build question data
                $questionData = [
                    'cbt_bank_id'   => $this->bank->id,
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
                                    $matchingPairs[trim($parts[0])] = trim($parts[1]);
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
                        'A' => ['text' => 'opsi_a', 'img_col' => 'F'],
                        'B' => ['text' => 'opsi_b', 'img_col' => 'H'],
                        'C' => ['text' => 'opsi_c', 'img_col' => 'J'],
                        'D' => ['text' => 'opsi_d', 'img_col' => 'L'],
                        'E' => ['text' => 'opsi_e', 'img_col' => 'N'],
                    ];
                    
                    $correctAnswers = array_map('trim', explode(',', strtoupper(trim($row['jawaban_benar'] ?? 'A'))));
                    $hasAnyOption = false;

                    foreach ($optionConfigs as $letter => $cfg) {
                        $optionText = trim($row[$cfg['text']] ?? '');
                        
                        // Check for embedded image in the designated column
                        $optImagePath = $this->getDrawingFromCell($cfg['img_col'] . $rowNumber);
                        
                        if (empty($optionText) && !$optImagePath) {
                            continue;
                        }

                        $hasAnyOption = true;

                        // Fallback to manual upload reference if no embedded image
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
