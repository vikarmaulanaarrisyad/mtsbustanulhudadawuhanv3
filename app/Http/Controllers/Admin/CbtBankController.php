<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtBank;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use App\Models\Subject;
use App\Models\Teacher;
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
}
