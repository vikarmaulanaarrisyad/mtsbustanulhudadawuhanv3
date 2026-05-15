<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppSetting;
use App\Models\SppBilling;
use App\Models\SppPayment;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class SppController extends Controller
{
    // ==================== SETTINGS ====================

    public function settings()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        return view('admin.spp.settings', compact('academicYears'));
    }

    public function settingsData()
    {
        $query = SppSetting::with('academicYear');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('academic_year_name', fn($r) => $r->academicYear->academic_year ?? '-')
            ->editColumn('amount', fn($r) => 'Rp ' . number_format($r->amount, 0, ',', '.'))
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editSetting(`' . route('admin.spp.settings.show', $r->id) . '`)" class="btn btn-xs btn-info"><i class="fas fa-pencil-alt"></i></button>
                    <button onclick="deleteSetting(`' . route('admin.spp.settings.destroy', $r->id) . '`)" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeSetting(Request $request)
    {
        $request->validate([
            'class_level' => 'required|integer|between:1,12',
            'amount' => 'required|numeric',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        SppSetting::updateOrCreate(
            ['class_level' => $request->class_level, 'academic_year_id' => $request->academic_year_id],
            ['amount' => $request->amount, 'description' => $request->description]
        );

        return response()->json(['message' => 'Pengaturan SPP berhasil disimpan']);
    }

    public function showSetting($id)
    {
        return response()->json(['data' => SppSetting::findOrFail($id)]);
    }

    public function destroySetting($id)
    {
        SppSetting::findOrFail($id)->delete();
        return response()->json(['message' => 'Pengaturan SPP berhasil dihapus']);
    }

    // ==================== BILLING & PAYMENTS ====================

    public function billing(Request $request)
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        return view('admin.spp.billing', compact('academicYears', 'classGroups'));
    }

    public function billingData(Request $request)
    {
        $query = SppBilling::with(['student', 'academicYear']);

        if ($request->class_group_id) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('student_class_group_id', $request->class_group_id);
            });
        }

        if ($request->month) $query->where('month', $request->month);
        if ($request->year) $query->where('year', $request->year);
        if ($request->status) $query->where('status', $request->status);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('student_name', fn($r) => $r->student->nama_lengkap ?? '-')
            ->addColumn('class_name', fn($r) => $r->student->kelas_lengkap ?? '-')
            ->editColumn('month', fn($r) => $this->getMonthName($r->month) . ' ' . $r->year)
            ->editColumn('amount', fn($r) => 'Rp ' . number_format($r->amount, 0, ',', '.'))
            ->addColumn('paid', fn($r) => 'Rp ' . number_format($r->paid_amount, 0, ',', '.'))
            ->addColumn('status_badge', function($r) {
                if ($r->status == 'Paid') return '<span class="badge badge-success">Lunas</span>';
                if ($r->status == 'Partial') return '<span class="badge badge-info">Sebagian</span>';
                return '<span class="badge badge-danger">Belum Bayar</span>';
            })
            ->addColumn('action', function($r) {
                $btn = '<div class="btn-group">';
                if ($r->status != 'Paid') {
                    $btn .= '<button onclick="payModal(' . $r->id . ', ' . $r->remaining_amount . ')" class="btn btn-xs btn-success"><i class="fas fa-money-bill-wave"></i> Bayar</button>';
                    $btn .= '<button onclick="editBill(' . $r->id . ', ' . $r->amount . ')" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></button>';
                }
                $btn .= '<button onclick="viewHistory(' . $r->id . ')" class="btn btn-xs btn-info"><i class="fas fa-history"></i></button>';
                $btn .= '<a href="' . route('admin.spp.print_card', $r->student_id) . '" target="_blank" class="btn btn-xs btn-primary"><i class="fas fa-print"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function generateBills(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $students = Student::active()->whereHas('classGroup', function($q) use ($request) {
            $q->where('academic_year_id', $request->academic_year_id);
        })->get();
        $generated = 0;

        foreach ($students as $student) {
            $level = $student->classGroup->class_level ?? null;
            if (!$level) continue;

            $setting = SppSetting::where('class_level', $level)
                ->where('academic_year_id', $request->academic_year_id)
                ->first();

            if (!$setting) continue;

            $exists = SppBilling::where('student_id', $student->id)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();

            if (!$exists) {
                SppBilling::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $request->academic_year_id,
                    'month' => $request->month,
                    'year' => $request->year,
                    'amount' => $setting->amount,
                    'status' => 'Unpaid',
                    'due_date' => $request->year . '-' . $request->month . '-10', // Default due date 10th
                ]);
                $generated++;
            }
        }

        return response()->json(['message' => "$generated tagihan berhasil digenerate."]);
    }

    public function updateBill(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|min:0']);
        $billing = SppBilling::findOrFail($id);
        $billing->update(['amount' => $request->amount]);
        
        // Update status if needed
        $paid = $billing->paid_amount;
        if ($paid >= $request->amount) {
            $billing->status = 'Paid';
        } elseif ($paid > 0) {
            $billing->status = 'Partial';
        } else {
            $billing->status = 'Unpaid';
        }
        $billing->save();

        return response()->json(['message' => 'Tagihan berhasil diperbarui.']);
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'spp_billing_id' => 'required|exists:spp_billings,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $billing = SppBilling::findOrFail($request->spp_billing_id);
        
        if ($request->amount > $billing->remaining_amount) {
            return response()->json(['message' => 'Jumlah bayar melebihi sisa tagihan.'], 422);
        }

        DB::beginTransaction();
        try {
            SppPayment::create([
                'spp_billing_id' => $billing->id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'receiver_id' => Auth::id(),
                'receipt_number' => 'RCP-' . time(),
                'notes' => $request->notes
            ]);

            $newPaid = $billing->paid_amount + $request->amount;
            if ($newPaid >= $billing->amount) {
                $billing->status = 'Paid';
            } else {
                $billing->status = 'Partial';
            }
            $billing->save();

            DB::commit();
            return response()->json(['message' => 'Pembayaran berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function paymentHistory($billing_id)
    {
        $payments = SppPayment::with('receiver')->where('spp_billing_id', $billing_id)->latest()->get();
        return response()->json(['data' => $payments]);
    }

    // ==================== REPORTS & PRINT ====================

    public function report(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $monthlyIncome = SppPayment::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('SUM(amount) as total')
        )
        ->whereYear('payment_date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();

        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartData[] = $monthlyIncome[$m] ?? 0;
        }

        return view('admin.spp.report', compact('year', 'chartData'));
    }

    public function printCard($student_id)
    {
        $student = Student::with(['classGroup', 'sppBillings' => function($q) {
            $q->orderBy('year')->orderBy('month');
        }])->findOrFail($student_id);
        
        $setting = \App\Models\Setting::first();
        return view('admin.spp.print_card', compact('student', 'setting'));
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$month] ?? '';
    }
}
