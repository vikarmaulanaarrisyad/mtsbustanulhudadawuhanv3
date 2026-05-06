<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        return view('admin.payrolls.index', compact('month', 'year'));
    }

    public function data(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $query = Payroll::with('teacher')->where('month', $month)->where('year', $year)->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('teacher_name', function ($r) {
                return $r->teacher->name;
            })
            ->addColumn('teacher_nip', function ($r) {
                return $r->teacher->nip ?? '-';
            })
            ->addColumn('net_salary_formatted', function ($r) {
                return 'Rp ' . number_format($r->net_salary, 0, ',', '.');
            })
            ->addColumn('status_badge', function ($r) {
                if ($r->payment_status == 'Paid') {
                    return '<span class="badge badge-success px-3 py-2 rounded-pill">Telah Dibayar</span>';
                }
                return '<span class="badge badge-warning px-3 py-2 rounded-pill">Pending</span>';
            })
            ->addColumn('action', function ($r) {
                $btns = '<div class="btn-group">';
                $btns .= '<a href="'.route('payrolls.show', $r->id).'" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>';
                $btns .= '<a href="'.route('payrolls.print', $r->id).'" class="btn btn-sm btn-primary" title="Cetak Slip"><i class="fas fa-print"></i></a>';
                $btns .= '<a href="'.route('payrolls.download_pdf', $r->id).'" target="_blank" class="btn btn-sm btn-danger" title="Preview PDF"><i class="fas fa-file-pdf"></i></a>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|string|size:2',
            'year' => 'required|string|size:4',
        ]);

        $month = $request->month;
        $year = $request->year;

        $teachers = Teacher::all();
        $generatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($teachers as $teacher) {
                // Check if already generated
                $exists = Payroll::where('teacher_id', $teacher->id)
                                 ->where('month', $month)
                                 ->where('year', $year)
                                 ->exists();

                if (!$exists) {
                    Payroll::create([
                        'teacher_id' => $teacher->id,
                        'month' => $month,
                        'year' => $year,
                        'base_salary' => $teacher->base_salary,
                        'total_allowance' => 0,
                        'total_deduction' => 0,
                        'net_salary' => $teacher->base_salary,
                        'payment_status' => 'Pending'
                    ]);
                    $generatedCount++;
                }
            }
            DB::commit();
            return response()->json(['message' => "$generatedCount Gaji berhasil digenerate untuk periode $month-$year."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $payroll = Payroll::with(['teacher', 'details'])->findOrFail($id);
        return view('admin.payrolls.show', compact('payroll'));
    }

    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:allowance,deduction',
            'name' => 'required|string|max:150',
            'amount' => 'required|numeric|min:1',
        ]);

        $payroll = Payroll::findOrFail($id);
        
        if ($payroll->payment_status == 'Paid') {
            return back()->with('error', 'Gaji sudah dibayarkan, tidak dapat diubah.');
        }

        DB::transaction(function () use ($payroll, $request) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'type' => $request->type,
                'name' => $request->name,
                'amount' => $request->amount,
            ]);

            $this->recalculatePayroll($payroll);
        });

        return back()->with('success', 'Rincian berhasil ditambahkan.');
    }

    public function destroyDetail($id)
    {
        $detail = PayrollDetail::findOrFail($id);
        $payroll = $detail->payroll;

        if ($payroll->payment_status == 'Paid') {
            return back()->with('error', 'Gaji sudah dibayarkan, tidak dapat diubah.');
        }

        DB::transaction(function () use ($detail, $payroll) {
            $detail->delete();
            $this->recalculatePayroll($payroll);
        });

        return back()->with('success', 'Rincian berhasil dihapus.');
    }

    public function pay(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->update([
            'payment_status' => 'Paid',
            'payment_date' => date('Y-m-d')
        ]);

        return back()->with('success', 'Gaji berhasil ditandai sebagai telah dibayar.');
    }

    public function print($id)
    {
        $payroll = Payroll::with(['teacher', 'details'])->findOrFail($id);
        $setting = \App\Models\Setting::first();
        return view('admin.payrolls.print', compact('payroll', 'setting'));
    }

    public function downloadPdf($id)
    {
        $payroll = Payroll::with(['teacher', 'details'])->findOrFail($id);
        $setting = \App\Models\Setting::first();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.payrolls.pdf', compact('payroll', 'setting'))
               ->setPaper('a4', 'portrait');
        
        $filename = 'Slip_Gaji_' . str_replace(' ', '_', $payroll->teacher->name) . '_' . $payroll->month . '_' . $payroll->year . '.pdf';
        return $pdf->stream($filename);
    }

    private function recalculatePayroll($payroll)
    {
        $totalAllowance = $payroll->details()->where('type', 'allowance')->sum('amount');
        $totalDeduction = $payroll->details()->where('type', 'deduction')->sum('amount');
        $netSalary = $payroll->base_salary + $totalAllowance - $totalDeduction;

        $payroll->update([
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'net_salary' => $netSalary,
        ]);
    }
}
