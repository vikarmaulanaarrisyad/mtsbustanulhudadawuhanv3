<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BosIncome;
use App\Models\BosExpenditure;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Imports\BosActivityImport;
use App\Imports\BosItemImport;
use App\Imports\BosExpenseTypeImport;
use App\Imports\BosProgramImport;
use App\Imports\BosMasterRkamImport;
use App\Models\BosActivity;
use App\Models\BosItem;
use App\Models\BosExpenseType;
use App\Models\BosProgram;
use App\Models\BosMasterRkam;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

use App\Helpers\NumberHelper;
use App\Models\Setting;

class BosFundController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        
        $stats = [
            'total_income' => BosIncome::sum('amount'),
            'total_expenditure' => BosExpenditure::sum('amount'),
        ];
        $stats['balance'] = $stats['total_income'] - $stats['total_expenditure'];

        return view('admin.bos.index', compact('academicYears', 'stats'));
    }

    public function items()
    {
        return view('admin.bos.master.items');
    }

    public function itemsData()
    {
        $query = BosItem::query();
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('harga_1', fn($r) => 'Rp ' . number_format($r->harga_1, 0, ',', '.'))
            ->make(true);
    }

    public function activities()
    {
        return view('admin.bos.master.activities');
    }

    public function activitiesData()
    {
        return DataTables::of(BosActivity::query())->addIndexColumn()->make(true);
    }

    public function expenseTypes()
    {
        return view('admin.bos.master.expense_types');
    }

    public function expenseTypesData()
    {
        return DataTables::of(BosExpenseType::query())->addIndexColumn()->make(true);
    }

    public function rkam()
    {
        return view('admin.bos.master.rkam');
    }

    public function rkamData()
    {
        return DataTables::of(BosMasterRkam::query())->addIndexColumn()->make(true);
    }

    public function downloadItemsTemplate()
    {
        $headers = ['Tahun', 'Kategori', 'Kode Kateg', 'Nama Kateg', 'Kode Provi', 'Kode Kabk', 'Kode', 'Nama', 'Spesifikasi', 'Satuan', 'Jenis Pemb', 'Harga 1', 'Harga 2', 'Harga 3'];
        return Excel::download(new \App\Exports\BosTemplateExport($headers), 'Template_Komponen_BOS.xlsx');
    }

    public function downloadActivitiesTemplate()
    {
        $headers = ['kode', 'nama', 'kategori'];
        return Excel::download(new \App\Exports\BosTemplateExport($headers), 'Template_Kategori_Kegiatan.xlsx');
    }

    public function downloadExpenseTypesTemplate()
    {
        $headers = ['kode_kate', 'kategori', 'kode_jenis', 'jenis', 'deskripsi'];
        return Excel::download(new \App\Exports\BosTemplateExport($headers), 'Template_Jenis_Belanja.xlsx');
    }

    public function downloadRkamTemplate()
    {
        $headers = ['kode_snp', 'snp', 'kode_kegiatan', 'nama_kegiatan', 'kode_sub_kegiatan', 'sub_kegiatan'];
        return Excel::download(new \App\Exports\BosTemplateExport($headers), 'Template_Master_RKAM.xlsx');
    }

    public function truncateItems()
    {
        BosItem::truncate();
        return response()->json(['message' => 'Seluruh data komponen biaya berhasil dihapus']);
    }

    public function truncateActivities()
    {
        BosActivity::truncate();
        return response()->json(['message' => 'Seluruh data kategori kegiatan berhasil dihapus']);
    }

    public function truncateExpenseTypes()
    {
        BosExpenseType::truncate();
        return response()->json(['message' => 'Seluruh data jenis belanja berhasil dihapus']);
    }

    public function truncateRkam()
    {
        BosMasterRkam::truncate();
        return response()->json(['message' => 'Seluruh data master RKAM berhasil dihapus']);
    }

    public function incomeData(Request $request)
    {
        $query = BosIncome::with('academicYear');

        if ($request->academic_year_id) $query->where('academic_year_id', $request->academic_year_id);
        if ($request->level) $query->where('level', $request->level);

        $query->orderBy('date', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', fn($r) => 'Rp ' . number_format($r->amount, 0, ',', '.'))
            ->editColumn('date', fn($r) => date('d/m/Y', strtotime($r->date)))
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editIncome(' . $r->id . ')" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteIncome(' . $r->id . ')" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                </div>';
            })
            ->make(true);
    }

    public function storeIncome(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'level' => 'required|in:MI,MTs,MA',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'source' => 'required|string|max:255',
        ]);

        BosIncome::updateOrCreate(['id' => $request->id], $request->all());
        return response()->json(['message' => 'Data Pendapatan BOS berhasil disimpan']);
    }

    public function expenditureData(Request $request)
    {
        $query = BosExpenditure::with('academicYear');

        if ($request->academic_year_id) $query->where('academic_year_id', $request->academic_year_id);
        if ($request->level) $query->where('level', $request->level);

        $query->orderBy('realized_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', fn($r) => 'Rp ' . number_format($r->amount, 0, ',', '.'))
            ->editColumn('realized_at', fn($r) => date('d/m/Y', strtotime($r->realized_at)))
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('admin.bos.expenditure.print', $r->id) . '" target="_blank" class="btn btn-xs btn-success"><i class="fas fa-print"></i></a>
                    <button onclick="editExpenditure(' . $r->id . ')" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteExpenditure(' . $r->id . ')" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                </div>';
            })
            ->make(true);
    }

    public function storeExpenditure(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'level' => 'required|in:MI,MTs,MA',
            'receipt_number' => 'required|string',
            'noted_at' => 'required|date',
            'realized_at' => 'required|date',
            'amount' => 'required|numeric',
            'category' => 'required|string|max:255',
            'receiver' => 'required|string|max:255',
        ]);

        $data = $request->except([
            'evidence', 'expense_category', 'expense_type', 
            'program_code', 'program_name',
            'kode_snp', 'snp', 'kode_kegiatan', 'nama_kegiatan', 'kode_sub_kegiatan', 'sub_kegiatan',
            'kode_kate', 'kategori', 'kode_jenis', 'jenis', 'deskripsi',
            'item_name', 'item_code', 'item_specification', 'item_unit', 'item_payment_type', 'item_price_1', 'item_price_2', 'item_price_3'
        ]);
        $data['expense_category'] = $request->expense_category;
        $data['expense_type'] = $request->expense_type;
        $data['program_code'] = $request->program_code;
        $data['program_name'] = $request->program_name;
        $data['kode_snp'] = $request->kode_snp;
        $data['snp'] = $request->snp;
        $data['kode_kegiatan'] = $request->kode_kegiatan;
        $data['nama_kegiatan'] = $request->nama_kegiatan;
        $data['kode_sub_kegiatan'] = $request->kode_sub_kegiatan;
        $data['sub_kegiatan'] = $request->sub_kegiatan;
        $data['kode_kate'] = $request->kode_kate;
        $data['kategori'] = $request->kategori;
        $data['kode_jenis'] = $request->kode_jenis;
        $data['jenis'] = $request->jenis;
        $data['deskripsi'] = $request->deskripsi;
        $data['item_name'] = $request->item_name;
        $data['item_code'] = $request->item_code;
        $data['item_specification'] = $request->item_specification;
        $data['item_unit'] = $request->item_unit;
        $data['item_payment_type'] = $request->item_payment_type;
        $data['item_price_1'] = $request->item_price_1;
        $data['item_price_2'] = $request->item_price_2;
        $data['item_price_3'] = $request->item_price_3;
        $data['date'] = $request->realized_at; // Sync legacy date column if still used
        
        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('bos_evidence', 'public');
        }

        BosExpenditure::updateOrCreate(['id' => $request->id], $data);
        return response()->json(['message' => 'Data Pengeluaran BOS berhasil disimpan']);
    }

    public function deleteIncome($id)
    {
        BosIncome::destroy($id);
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function deleteExpenditure($id)
    {
        $exp = BosExpenditure::findOrFail($id);
        if ($exp->evidence_path) Storage::disk('public')->delete($exp->evidence_path);
        $exp->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    public function showIncome($id)
    {
        return response()->json(['data' => BosIncome::findOrFail($id)]);
    }

    public function showExpenditure($id)
    {
        return response()->json(['data' => BosExpenditure::findOrFail($id)]);
    }

    public function generateReceiptNumber(Request $request)
    {
        $level = $request->level ?? 'MTs';
        $year = date('Y');
        $month = date('m');
        
        $last = BosExpenditure::whereYear('realized_at', $year)
            ->where('level', $level)
            ->orderBy('id', 'desc')
            ->first();

        $count = 1;
        if ($last && $last->receipt_number) {
            $parts = explode('/', $last->receipt_number);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $count = (int)$parts[0] + 1;
            }
        }

        $num = str_pad($count, 3, '0', STR_PAD_LEFT);
        $receipt = "{$num}/BOS/{$level}/{$month}/{$year}";

        return response()->json(['receipt_number' => $receipt]);
    }

    public function printReceipt($id)
    {
        $exp = BosExpenditure::with('academicYear')->findOrFail($id);
        $setting = Setting::first();
        $terbilang = NumberHelper::terbilang($exp->amount) . " Rupiah";

        return view('admin.bos.print_receipt', compact('exp', 'setting', 'terbilang'));
    }

    public function importActivity(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->store('temp');
        Excel::import(new BosActivityImport, storage_path('app/' . $path));
        Storage::delete($path);
        return response()->json(['message' => 'Kategori Kegiatan berhasil diimport']);
    }

    public function importItem(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->store('temp');
        Excel::import(new BosItemImport, storage_path('app/' . $path));
        Storage::delete($path);
        return response()->json(['message' => 'Daftar Barang Belanja berhasil diimport']);
    }

    public function searchActivity(Request $request)
    {
        $search = $request->search;
        $activities = BosActivity::where('name', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%")
            ->limit(100)
            ->get();
        return response()->json($activities);
    }

    public function searchItem(Request $request)
    {
        $search = $request->search;
        $data = BosItem::where('name', 'LIKE', "%$search%")
            ->orWhere('code', 'LIKE', "%$search%")
            ->orWhere('nama_kateg', 'LIKE', "%$search%")
            ->limit(100)
            ->get();
        return response()->json($data);
    }

    public function importExpenseType(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->store('temp');
        Excel::import(new BosExpenseTypeImport, storage_path('app/' . $path));
        Storage::delete($path);
        return response()->json(['message' => 'Data Jenis Belanja berhasil diimport']);
    }

    public function searchExpenseType(Request $request)
    {
        $search = $request->search;
        $data = BosExpenseType::where('jenis', 'LIKE', "%$search%")
            ->orWhere('kategori', 'LIKE', "%$search%")
            ->orWhere('kode_jenis', 'LIKE', "%$search%")
            ->limit(100)
            ->get();
        return response()->json($data);
    }

    public function importProgram(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->store('temp');
        Excel::import(new BosProgramImport, storage_path('app/' . $path));
        Storage::delete($path);
        return response()->json(['message' => 'Data Program berhasil diimport']);
    }

    public function searchProgram(Request $request)
    {
        $search = $request->search;
        $data = BosProgram::where('name', 'LIKE', "%$search%")
            ->orWhere('code', 'LIKE', "%$search%")
            ->limit(100)
            ->get();
        return response()->json($data);
    }

    public function importRkam(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        $path = $request->file('file')->store('temp');
        Excel::import(new BosMasterRkamImport, storage_path('app/' . $path));
        Storage::delete($path);
        return response()->json(['message' => 'Data RKAM berhasil diimport']);
    }

    public function searchRkam(Request $request)
    {
        $search = $request->search;
        $data = BosMasterRkam::where('sub_kegiatan', 'LIKE', "%$search%")
            ->orWhere('nama_kegiatan', 'LIKE', "%$search%")
            ->orWhere('snp', 'LIKE', "%$search%")
            ->orWhere('kode_sub_kegiatan', 'LIKE', "%$search%")
            ->limit(100)
            ->get();
        return response()->json($data);
    }
}
