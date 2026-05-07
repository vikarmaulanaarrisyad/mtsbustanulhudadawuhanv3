<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtBank;
use App\Models\CbtQuestion;
use App\Models\CbtOption;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
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
            'options' => 'required|array|min:2',
            'correct_option' => 'required',
        ]);

        $question = $bank->questions()->create([
            'question_text' => $request->question_text,
            'score_weight' => $request->score_weight ?? 1,
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => ($index == $request->correct_option)
            ]);
        }

        return redirect()->back()->with('success', 'Soal berhasil ditambahkan');
    }

    public function destroyQuestion(CbtQuestion $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }
}
