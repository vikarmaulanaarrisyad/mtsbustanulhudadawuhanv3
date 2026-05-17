<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSaving;
use App\Models\StudentSavingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StudentSavingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isGuru = $user->hasRole('Guru');
        $isSiswa = $user->hasRole('Siswa');
        $homeroomClass = null;
        $transactions = collect([]);

        if ($isGuru) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $homeroomClass = \App\Models\ClassGroup::where('teacher_id', $teacher->id)->first();
            
            if (!$homeroomClass) {
                return redirect()->route('guru.dashboard')->with('error', 'Fitur Tabungan Siswa hanya tersedia untuk Wali Kelas.');
            }
            
            $studentIds = \App\Models\Student::where('student_class_group_id', $homeroomClass->id)->pluck('id');
            $totalSavings = StudentSaving::whereIn('student_id', $studentIds)->sum('balance');
            $totalTransactionsToday = StudentSavingTransaction::whereIn('student_saving_id', StudentSaving::whereIn('student_id', $studentIds)->pluck('id'))
                ->whereDate('created_at', date('Y-m-d'))->count();
            $totalDepositsToday = StudentSavingTransaction::whereIn('student_saving_id', StudentSaving::whereIn('student_id', $studentIds)->pluck('id'))
                ->whereDate('created_at', date('Y-m-d'))->where('type', 'debit')->sum('amount');
        } elseif ($isSiswa) {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            $saving = StudentSaving::where('student_id', $student->id)->first();
            
            $totalSavings = $saving->balance ?? 0;
            $totalTransactionsToday = $saving ? StudentSavingTransaction::where('student_saving_id', $saving->id)->count() : 0;
            $totalDepositsToday = $saving ? StudentSavingTransaction::where('student_saving_id', $saving->id)->where('type', 'debit')->sum('amount') : 0;
            
            $transactions = $saving ? StudentSavingTransaction::where('student_saving_id', $saving->id)->latest()->paginate(10) : collect([]);
        } else {
            $totalSavings = StudentSaving::sum('balance');
            $totalTransactionsToday = StudentSavingTransaction::whereDate('created_at', date('Y-m-d'))->count();
            $totalDepositsToday = StudentSavingTransaction::whereDate('created_at', date('Y-m-d'))->where('type', 'debit')->sum('amount');
        }
        
        $view = 'admin.finance.savings.index';
        if ($isGuru) $view = 'guru.savings.index';
        if ($isSiswa) $view = 'siswa.savings.index';
        
        return view($view, compact('totalSavings', 'totalTransactionsToday', 'totalDepositsToday', 'isGuru', 'isSiswa', 'homeroomClass', 'transactions'));
    }

    public function data(Request $request)
    {
        $user = auth()->user();
        $query = Student::with(['savings', 'classGroup'])->where('is_active', true);

        if ($user->hasRole('Guru')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $homeroomClass = \App\Models\ClassGroup::where('teacher_id', $teacher->id)->first();
            if ($homeroomClass) {
                $query->where('student_class_group_id', $homeroomClass->id);
            } else {
                $query->where('id', 0); // No students for non-homeroom teachers
            }
        } elseif ($request->class_group_id) {
            $query->where('student_class_group_id', $request->class_group_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('class', function($s) {
                return $s->classGroup->kelas_lengkap ?? '-';
            })
            ->addColumn('balance', function($s) {
                return $s->savings->balance ?? 0;
            })
            ->addColumn('action', function ($s) use ($user) {
                $historyRoute = $user->hasRole('Guru') ? route('guru.savings.history', $s->id) : route('admin.savings.history', $s->id);
                return '
                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                    <button onclick="transactionForm(' . $s->id . ', `' . addslashes($s->nama_lengkap) . '`, `debit`)" class="btn btn-xs btn-success border-0 px-2" title="Setor Tunai">
                        <i class="fas fa-plus mr-1"></i> SETOR
                    </button>
                    <button onclick="transactionForm(' . $s->id . ', `' . addslashes($s->nama_lengkap) . '`, `credit`)" class="btn btn-xs btn-warning border-0 px-2" title="Tarik Tunai">
                        <i class="fas fa-minus mr-1"></i> TARIK
                    </button>
                    <a href="' . $historyRoute . '" class="btn btn-xs btn-info border-0 px-2" title="Riwayat">
                        <i class="fas fa-history mr-1"></i> LOG
                    </a>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:255',
        ]);

        // Authorization check for Guru
        if ($user->hasRole('Guru')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            $homeroomClass = \App\Models\ClassGroup::where('teacher_id', $teacher->id)->first();
            $student = Student::find($request->student_id);
            
            if (!$homeroomClass || $student->student_class_group_id != $homeroomClass->id) {
                return response()->json(['message' => 'Anda hanya dapat menginput tabungan untuk siswa di kelas perwalian Anda.'], 403);
            }
        }

        try {
            DB::beginTransaction();

            $saving = StudentSaving::firstOrCreate(
                ['student_id' => $request->student_id],
                ['balance' => 0]
            );

            if ($request->type == 'credit' && $saving->balance < $request->amount) {
                return response()->json(['message' => 'Saldo tidak mencukupi!'], 422);
            }

            // Update balance
            if ($request->type == 'debit') {
                $saving->balance += $request->amount;
            } else {
                $saving->balance -= $request->amount;
            }
            $saving->last_transaction_at = now();
            $saving->save();

            // Create transaction record
            StudentSavingTransaction::create([
                'student_saving_id' => $saving->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'current_balance' => $saving->balance,
                'description' => $request->description,
                'reference_no' => StudentSavingTransaction::generateReferenceNo($request->type),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function history($id)
    {
        $student = Student::with(['savings', 'classGroup'])->findOrFail($id);
        $transactions = StudentSavingTransaction::where('student_saving_id', $student->savings->id ?? 0)
            ->with('creator')
            ->latest()
            ->paginate(15);
            
        $isGuru = auth()->user()->hasRole('Guru');
        $view = $isGuru ? 'guru.savings.history' : 'admin.finance.savings.history';
        return view($view, compact('student', 'transactions', 'isGuru'));
    }

    public function print($id)
    {
        $student = Student::with(['savings', 'classGroup'])->findOrFail($id);
        $transactions = StudentSavingTransaction::where('student_saving_id', $student->savings->id ?? 0)
            ->with('creator')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $setting = \App\Models\Setting::first();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.finance.savings.pdf', compact('student', 'transactions', 'setting'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Kartu_Tabungan_' . str_replace(' ', '_', $student->nama_lengkap) . '.pdf');
    }
}
