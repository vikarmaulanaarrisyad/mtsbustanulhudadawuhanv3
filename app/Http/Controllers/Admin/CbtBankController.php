<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtBank;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\GeminiAiService;
use App\Exports\CbtTemplateExport;
use App\Imports\CbtQuestionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CbtBankController extends Controller
{
    public function index()
    {
        return view('admin.cbt.bank.index');
    }

    public function data(Request $request)
    {
        $query = CbtBank::with(['subject', 'teacher'])->withCount('questions');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                            <a href="'.route('admin.cbt.bank.show', $row->id).'" class="btn btn-sm btn-info" title="Kelola Soal"><i class="fas fa-list"></i> Kelola Soal</a>
                            <button onclick="editBank('.$row->id.')" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteBank('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject_id' => 'required',
            'class_level' => 'required',
        ]);

        CbtBank::create($request->all());
        return response()->json(['message' => 'Bank Soal berhasil ditambahkan']);
    }

    public function edit(CbtBank $bank)
    {
        return response()->json($bank);
    }

    public function update(Request $request, CbtBank $bank)
    {
        $request->validate([
            'name' => 'required',
            'subject_id' => 'required',
            'class_level' => 'required',
        ]);

        $bank->update($request->all());
        return response()->json(['message' => 'Bank Soal berhasil diperbarui']);
    }

    public function destroy(CbtBank $bank)
    {
        $bank->delete();
        return response()->json(['message' => 'Bank Soal berhasil dihapus']);
    }

    // ==========================================
    // MANAGE QUESTIONS
    // ==========================================
    public function show(CbtBank $bank)
    {
        $bank->load('questions.options');
        return view('admin.cbt.bank.show', compact('bank'));
    }

    public function storeQuestion(Request $request, CbtBank $bank)
    {
        $request->validate([
            'question_text' => 'required',
            'question_type' => 'required|in:pilihan_ganda,ganda_komplek,penjodohan,essay,uraian',
        ]);

        \DB::beginTransaction();
        try {
            $questionData = [
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'score_weight'  => $request->score_weight ?? 1,
            ];

            if ($request->hasFile('question_image')) {
                $imagePath = $request->file('question_image')->store('cbt/images', 'public');
                $questionData['question_image'] = $imagePath;
            }

            // Additional logic for specific types
            if ($request->question_type === 'penjodohan') {
                $premises = $request->matching_premises ?? [];
                $responses = $request->matching_responses ?? [];
                $pairs = [];
                foreach ($premises as $idx => $premise) {
                    if (!empty($premise) && !empty($responses[$idx])) {
                        $pairs[$premise] = $responses[$idx];
                    }
                }
                $questionData['matching_pairs'] = $pairs;
            } elseif (in_array($request->question_type, ['essay', 'uraian'])) {
                $questionData['answer_key'] = $request->answer_key;
            }

            $question = $bank->questions()->create($questionData);

            // Handle Options for PG/PGK
            if (in_array($request->question_type, ['pilihan_ganda', 'ganda_komplek'])) {
                $options = $request->options ?? [];
                $correctOptions = (array) ($request->correct_option ?? []);

                foreach ($options as $index => $optionText) {
                    if (empty(trim($optionText))) continue;

                    $optImagePath = null;
                    if ($request->hasFile("option_images.$index")) {
                        $optImagePath = $request->file("option_images.$index")->store('cbt/images', 'public');
                    }

                    $question->options()->create([
                        'option_text'  => $optionText,
                        'option_image' => $optImagePath,
                        'is_correct'   => in_array($index, $correctOptions),
                    ]);
                }
            }

            \DB::commit();
            return redirect()->back()->with('success', 'Soal berhasil ditambahkan');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error storing CBT question: ' . $e->getMessage());
            return redirect()->back()->with('warning', 'Gagal menyimpan soal: ' . $e->getMessage())->withInput();
        }
    }

    public function editQuestion(CbtQuestion $question)
    {
        $question->load('options');
        return response()->json($question);
    }

    public function updateQuestion(Request $request, CbtQuestion $question)
    {
        $request->validate([
            'question_text' => 'required',
            'question_type' => 'required|in:pilihan_ganda,ganda_komplek,penjodohan,essay,uraian',
        ]);

        \DB::beginTransaction();
        try {
            $questionData = [
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'score_weight'  => $request->score_weight ?? 1,
            ];

            if ($request->hasFile('question_image')) {
                $imagePath = $request->file('question_image')->store('cbt/images', 'public');
                $questionData['question_image'] = $imagePath;
            }

            if ($request->question_type === 'penjodohan') {
                $premises = $request->matching_premises ?? [];
                $responses = $request->matching_responses ?? [];
                $pairs = [];
                foreach ($premises as $idx => $premise) {
                    if (!empty($premise) && !empty($responses[$idx])) {
                        $pairs[$premise] = $responses[$idx];
                    }
                }
                $questionData['matching_pairs'] = $pairs;
            } elseif (in_array($request->question_type, ['essay', 'uraian'])) {
                $questionData['answer_key'] = $request->answer_key;
            }

            $question->update($questionData);

            if (in_array($request->question_type, ['pilihan_ganda', 'ganda_komplek'])) {
                $question->options()->delete();
                $options = $request->options ?? [];
                $correctOptions = (array) ($request->correct_option ?? []);

                foreach ($options as $index => $optionText) {
                    if (empty(trim($optionText))) continue;

                    $optImagePath = null;
                    if ($request->hasFile("option_images.$index")) {
                        $optImagePath = $request->file("option_images.$index")->store('cbt/images', 'public');
                    }

                    $question->options()->create([
                        'option_text'  => $optionText,
                        'option_image' => $optImagePath,
                        'is_correct'   => in_array($index, $correctOptions),
                    ]);
                }
            } else {
                $question->options()->delete();
            }

            \DB::commit();
            return redirect()->back()->with('success', 'Soal berhasil diperbarui');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error updating CBT question: ' . $e->getMessage());
            return redirect()->back()->with('warning', 'Gagal memperbarui soal: ' . $e->getMessage());
        }
    }

    public function destroyQuestion(CbtQuestion $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }

    // ==========================================
    // IMPORT / EXPORT TEMPLATE
    // ==========================================

    /**
     * Download Excel template for importing questions.
     */
    public function downloadTemplate(CbtBank $bank)
    {
        $filename = 'Template_Soal_' . Str::slug($bank->name) . '.xlsx';
        return Excel::download(new CbtTemplateExport($bank->name), $filename);
    }

    /**
     * Import questions from uploaded Excel file.
     */
    public function importQuestions(Request $request, CbtBank $bank)
    {
        // Disable debugbar to save memory
        if (class_exists('\Barryvdh\Debugbar\Facades\Debugbar')) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        // Increase limits for processing images in Excel
        ini_set('max_execution_time', 600); // 10 minutes
        ini_set('memory_limit', '512M');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // max 10MB
            'images.*' => 'nullable|image|max:5120', // max 5MB per image
        ]);

        // Collect uploaded images by their original filename
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedImages[$image->getClientOriginalName()] = $image;
            }
        }

        \Log::info('Starting CBT Question Import for bank ID: ' . $bank->id);
        $import = new CbtQuestionsImport($bank, $uploadedImages);
        Excel::import($import, $request->file('file'));
        \Log::info('Excel::import finished.');

        $importedCount = $import->getImportedCount();
        $errors = $import->getErrors();
        $summary = $import->getSummary();

        // Build summary message
        $summaryParts = [];
        $typeLabels = CbtQuestion::typeLabels();
        foreach ($summary as $type => $count) {
            if ($count > 0) {
                $summaryParts[] = ($typeLabels[$type] ?? $type) . ": {$count}";
            }
        }

        $message = "Berhasil mengimport {$importedCount} soal.";
        if (!empty($summaryParts)) {
            $message .= " (" . implode(', ', $summaryParts) . ")";
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->with('warning', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete all questions and images from a bank.
     */
    public function truncateQuestions(CbtBank $bank)
    {
        try {
            \DB::beginTransaction();
            $questions = $bank->questions()->get();
            
            foreach ($questions as $question) {
                // Delete question image
                if ($question->question_image) {
                    Storage::disk('public')->delete($question->question_image);
                }
                
                // Delete option images
                foreach ($question->options as $option) {
                    if ($option->option_image) {
                        Storage::disk('public')->delete($option->option_image);
                    }
                }
                
                // Delete the question (cascade to options should be handled by DB or Eloquent)
                $question->delete();
            }
            
            \DB::commit();
            return redirect()->back()->with('success', 'Semua soal dan file gambar berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error truncating CBT questions: ' . $e->getMessage());
            return redirect()->back()->with('warning', 'Gagal mengosongkan soal: ' . $e->getMessage());
        }
    }

    /**
     * Bulk upload images (Files or ZIP) and link them to existing questions via naming convention.
     */
    public function bulkUploadImages(Request $request, CbtBank $bank)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'file|max:20480', // 20MB per file/zip
        ]);

        $count = 0;
        $errors = [];
        $tempDir = storage_path('app/temp_zip_' . uniqid());

        try {
            foreach ($request->file('images') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                
                if ($ext === 'zip') {
                    // Process ZIP file
                    $zip = new \ZipArchive();
                    if ($zip->open($file->getRealPath()) === TRUE) {
                        if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);
                        $zip->extractTo($tempDir);
                        $zip->close();

                        // Get all files from extracted dir (recursive)
                        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
                        foreach ($files as $extractedFile) {
                            if (!$extractedFile->isDir()) {
                                $this->processImageFile($extractedFile->getPathname(), $extractedFile->getFilename(), $bank, $count, $errors);
                            }
                        }
                    } else {
                        $errors[] = "Gagal membuka file ZIP: " . $file->getClientOriginalName();
                    }
                } else {
                    // Process single image file
                    $this->processImageFile($file->getRealPath(), $file->getClientOriginalName(), $bank, $count, $errors);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error processing bulk images: ' . $e->getMessage());
            $errors[] = "Sistem Error: " . $e->getMessage();
        } finally {
            // Cleanup temp dir
            if (file_exists($tempDir)) {
                $it = new \RecursiveDirectoryIterator($tempDir, \RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach($files as $file) {
                    if ($file->isDir()) rmdir($file->getRealPath());
                    else unlink($file->getRealPath());
                }
                rmdir($tempDir);
            }
        }

        $msg = "Berhasil memproses {$count} gambar.";
        if (!empty($errors)) {
            return redirect()->back()->with('warning', $msg)->with('import_errors', array_unique($errors));
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Internal helper to process a single image file (from upload or zip).
     */
    private function processImageFile($fullPath, $originalName, $bank, &$count, &$errors)
    {
        $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return; // Skip non-image files
        }
        
        $processed = false;

        // 1. Try literal filename match (from Excel "Gambar Soal" or "Gambar Opsi" column)
        // Check if any question has this filename in its image field
        $questionLiteral = $bank->questions()->where('question_image', $originalName)->first();
        if ($questionLiteral) {
            $newFilename = \Str::uuid() . '.' . $ext;
            $destination = 'cbt/images/' . $newFilename;
            Storage::disk('public')->put($destination, file_get_contents($fullPath));
            $questionLiteral->update(['question_image' => $destination]);
            $count++;
            $processed = true;
        }

        // Check if any option has this filename in its image field
        $optionsLiteral = \App\Models\CbtOption::whereHas('question', function($q) use ($bank) {
            $q->where('cbt_bank_id', $bank->id);
        })->where('option_image', $originalName)->get();
        
        foreach ($optionsLiteral as $opt) {
            $newFilename = \Str::uuid() . '.' . $ext;
            $destination = 'cbt/images/' . $newFilename;
            Storage::disk('public')->put($destination, file_get_contents($fullPath));
            $opt->update(['option_image' => $destination]);
            if (!$processed) $count++; 
            $processed = true;
        }

        if ($processed) return;

        // 2. Fallback to Naming Convention: "1.jpg", "1_A.jpg", "1_P1.jpg", "1_R1.jpg"
        if (preg_match('/^(?:soal_)?([A-Za-z0-9\-]+)(?:_([A-E]))?$/i', $nameWithoutExt, $matches)) {
            $questionNo = $matches[1];
            $optionLetter = isset($matches[2]) ? strtoupper($matches[2]) : null;

            $question = $bank->questions()->where('no', $questionNo)->first();
            
            if ($question) {
                $newFilename = \Str::uuid() . '.' . $ext;
                $destination = 'cbt/images/' . $newFilename;
                Storage::disk('public')->put($destination, file_get_contents($fullPath));
                
                if ($optionLetter) {
                    $index = ord($optionLetter) - 65;
                    $option = $question->options()->get()->values()->get($index);
                    if ($option) {
                        if ($option->option_image && \Str::contains($option->option_image, 'cbt/images/')) {
                            Storage::disk('public')->delete($option->option_image);
                        }
                        $option->update(['option_image' => $destination]);
                        $count++;
                    } else {
                        $errors[] = "Soal No {$questionNo}: Opsi {$optionLetter} tidak ditemukan.";
                    }
                } else {
                    if ($question->question_image && \Str::contains($question->question_image, 'cbt/images/')) {
                        Storage::disk('public')->delete($question->question_image);
                    }
                    $question->update(['question_image' => $destination]);
                    $count++;
                }
                $processed = true;
            }
        } elseif (preg_match('/^(?:soal_)?([A-Za-z0-9\-]+)_([PR])([0-9]+)$/i', $nameWithoutExt, $matches)) {
            // Matching Pair Convention: 1_P1.jpg (Premise 1), 1_R1.jpg (Response 1)
            $questionNo = $matches[1];
            $type = strtoupper($matches[2]); 
            $idx = (int)$matches[3] - 1; 

            $question = $bank->questions()->where('no', $questionNo)->first();
            if ($question && $question->question_type === 'penjodohan') {
                $pairs = $question->matching_pairs;
                $keys = array_keys($pairs);
                $values = array_values($pairs);

                if ($idx < count($keys)) {
                    $newFilename = \Str::uuid() . '.' . $ext;
                    $destination = 'cbt/images/' . $newFilename;
                    Storage::disk('public')->put($destination, file_get_contents($fullPath));
                    $imgTag = "[IMG]{$destination}[/IMG]";

                    if ($type === 'P') {
                        $oldValue = $values[$idx];
                        unset($keys[$idx]); unset($values[$idx]);
                        $keys = array_values($keys); $values = array_values($values);
                        array_splice($keys, $idx, 0, [$imgTag]);
                        array_splice($values, $idx, 0, [$oldValue]);
                    } else {
                        $values[$idx] = $imgTag;
                    }

                    $question->update(['matching_pairs' => array_combine($keys, $values)]);
                    $count++;
                    $processed = true;
                }
            }
        }

        if (!$processed) {
            $errors[] = "File '{$originalName}' tidak cocok dengan No Soal manapun dan tidak ditemukan referensinya di kolom Gambar Excel.";
        }
    }

    /**
     * Generate questions using AI.
     */
    public function generateAiQuestions(Request $request, GeminiAiService $aiService)
    {
        $request->validate([
            'source_text' => 'required|string|min:50',
            'type' => 'required|in:pilihan_ganda,essay',
            'count' => 'required|integer|min:1|max:20',
        ]);

        try {
            $questions = $aiService->generateQuestions(
                $request->source_text,
                $request->type,
                $request->count
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save AI generated questions to bank.
     */
    public function saveAiQuestions(Request $request, CbtBank $bank)
    {
        $request->validate([
            'questions' => 'required|array',
            'type' => 'required|in:pilihan_ganda,essay',
        ]);

        \DB::beginTransaction();
        try {
            $type = $request->type;
            foreach ($request->questions as $qData) {
                if (empty($qData['question_text'])) continue;

                $question = $bank->questions()->create([
                    'question_text' => $qData['question_text'],
                    'question_type' => $type === 'pilihan_ganda' ? 'pilihan_ganda' : 'essay',
                    'score_weight'  => $qData['score_weight'] ?? ($type === 'pilihan_ganda' ? 1 : 5),
                    'answer_key'    => $qData['answer_key'] ?? null,
                ]);

                if ($type === 'pilihan_ganda' && isset($qData['options'])) {
                    foreach ($qData['options'] as $opt) {
                        $question->options()->create([
                            'option_text' => $opt['text'],
                            'is_correct'  => $opt['is_correct'] ?? false,
                        ]);
                    }
                }
            }

            \DB::commit();
            return response()->json(['success' => true, 'message' => count($request->questions) . ' soal berhasil disimpan ke bank.']);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
