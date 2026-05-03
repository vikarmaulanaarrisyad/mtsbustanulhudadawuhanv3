<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use App\Models\PpdbDocument;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PpdbRegistrantController extends Controller
{
    /**
     * Display PPDB dashboard with statistics and DataTable.
     */
    public function index()
    {
        $academicYear = AcademicYear::where('admission_semester', 1)->first();

        if (!$academicYear) {
            return redirect()->route('dashboard')
                ->with('error', 'Belum ada tahun ajaran dengan semester penerimaan aktif.');
        }

        $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $phases = AdmissionPhase::where('academic_year_id', $academicYear->id)->get();
        $types = AdmissionType::where('academic_year_id', $academicYear->id)->get();

        // Statistik
        $baseQuery = PpdbRegistrant::when($admission, fn($q) => $q->where('student_admission_id', $admission->id ?? 0));

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->byStatus('pending')->count(),
            'berkas_lengkap' => (clone $baseQuery)->byStatus('berkas_lengkap')->count(),
            'diterima' => (clone $baseQuery)->byStatus('diterima')->count(),
            'ditolak' => (clone $baseQuery)->byStatus('ditolak')->count(),
        ];

        // Chart Data: Registration Trend (Last 30 Days)
        $trendData = PpdbRegistrant::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chart Data: By Admission Type
        $typeDistribution = PpdbRegistrant::select('admission_type_id', DB::raw('count(*) as total'))
            ->with('admissionType')
            ->groupBy('admission_type_id')
            ->get()
            ->map(fn($item) => [
                'label' => $item->admissionType->admission_type_name ?? 'Lainnya',
                'value' => $item->total
            ]);

        $classGroups = ClassGroup::all();

        return view('admin.admission.ppdb.index', compact(
            'academicYear',
            'admission',
            'phases',
            'types',
            'stats',
            'trendData',
            'typeDistribution',
            'classGroups'
        ));
    }

    /**
     * DataTables server-side data.
     */
    public function data(Request $request)
    {
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $admission = StudentAdmission::where('academic_year_id', $academicYear->id ?? 0)->first();

        $query = PpdbRegistrant::with(['admissionPhase', 'admissionType'])
            ->when($admission, fn($q) => $q->where('student_admission_id', $admission->id))
            ->where('status', '!=', PpdbRegistrant::STATUS_MOVED)
            ->when($request->phase_id, fn($q) => $q->where('admission_phase_id', $request->phase_id))
            ->when($request->type_id, fn($q) => $q->where('admission_type_id', $request->type_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('select_checkbox', function ($r) {
                return '<input type="checkbox" class="select-row" value="' . $r->id . '">';
            })
            ->addColumn('gelombang', fn($r) => $r->admissionPhase->phase_name ?? '-')
            ->addColumn('jalur', fn($r) => $r->admissionType->admission_type_name ?? '-')
            ->addColumn('jk_label', fn($r) => $r->jenis_kelamin === 'L' ? 'L' : 'P')
            ->addColumn('registration_number', function ($r) {
                return '<span class="font-weight-bold text-primary">' . $r->registration_number . '</span>' . 
                       ($r->letter_number ? '<br><small class="text-muted">SK: ' . $r->letter_number . '</small>' : '');
            })
            ->addColumn('status_badge', function ($r) {
                return '<span class="badge badge-' . $r->status_color . '">' . $r->status_label . '</span>';
            })
            ->addColumn('action', function ($r) {
                $btnView = '<button onclick="showDetail(' . $r->id . ')" class="btn btn-xs btn-info shadow-sm" title="Detail"><i class="fas fa-eye"></i></button>';
                $btnVerify = '<button onclick="openVerify(' . $r->id . ')" class="btn btn-xs btn-success shadow-sm" title="Verifikasi Berkas"><i class="fas fa-clipboard-check"></i></button>';
                $btnEdit = '<button onclick="editForm(`' . route('ppdb.show', $r->id) . '`)" class="btn btn-xs btn-primary shadow-sm" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
                $btnDelete = '<button onclick="deleteData(`' . route('ppdb.destroy', $r->id) . '`, `' . $r->nama_lengkap . '`)" class="btn btn-xs btn-danger shadow-sm" title="Hapus"><i class="fas fa-trash"></i></button>';
                
                $btnMove = '';
                if ($r->status == 'daftar_ulang_terverifikasi') {
                    $btnMove = '<button onclick="moveIndividualToStudent(' . $r->id . ', `' . $r->nama_lengkap . '`)" class="btn btn-xs btn-primary shadow-sm" title="Pindahkan ke Data Siswa"><i class="fas fa-user-check"></i></button>';
                }

                $btnPrint = '';
                if (in_array($r->status, ['diterima', 'ditolak', 'daftar_ulang', 'daftar_ulang_terverifikasi', 'sudah_masuk_siswa'])) {
                    $btnPrint = '<a href="' . route('ppdb.print_letter', $r->id) . '" target="_blank" class="btn btn-xs btn-secondary shadow-sm" title="Cetak SK"><i class="fas fa-file-pdf"></i></a>';
                }

                return '<div class="btn-group" style="gap:3px;">' . $btnView . $btnVerify . $btnEdit . $btnDelete . $btnMove . $btnPrint . '</div>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created registrant.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_hp_ortu' => 'required|max:20',
            'student_admission_id' => 'required|exists:student_admissions,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Periksa kembali data Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $admission = StudentAdmission::findOrFail($request->student_admission_id);

            $registrant = PpdbRegistrant::create([
                'registration_number' => PpdbRegistrant::generateRegistrationNumber($admission->admission_year),
                'student_admission_id' => $request->student_admission_id,
                'admission_phase_id' => $request->admission_phase_id,
                'admission_type_id' => $request->admission_type_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'alamat' => $request->alamat,
                'status' => 'pending',
            ]);

            // Upload foto
            if ($request->hasFile('foto')) {
                $registrant->foto = $request->file('foto')->store('ppdb/foto', 'public');
                $registrant->save();
            }

            // Upload berkas
            $docTypes = PpdbRegistrant::DOCUMENT_TYPES;
            foreach ($docTypes as $type => $name) {
                if ($request->hasFile("doc_{$type}")) {
                    $path = $request->file("doc_{$type}")->store('ppdb/documents/' . $registrant->id, 'public');
                    PpdbDocument::create([
                        'ppdb_registrant_id' => $registrant->id,
                        'document_name' => $name,
                        'document_type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pendaftar berhasil ditambahkan. No. Pendaftaran: ' . $registrant->registration_number,
                'data' => $registrant
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show registrant detail (JSON).
     */
    public function show($id)
    {
        $registrant = PpdbRegistrant::with(['documents', 'admissionPhase', 'admissionType', 'verifier'])
            ->findOrFail($id);

        return response()->json(['data' => $registrant]);
    }

    /**
     * Update registrant data.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_hp_ortu' => 'required|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $registrant = PpdbRegistrant::findOrFail($id);

            $registrant->update([
                'admission_phase_id' => $request->admission_phase_id,
                'admission_type_id' => $request->admission_type_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'alamat' => $request->alamat,
            ]);

            // Update foto
            if ($request->hasFile('foto')) {
                if ($registrant->foto) {
                    Storage::disk('public')->delete($registrant->foto);
                }
                $registrant->foto = $request->file('foto')->store('ppdb/foto', 'public');
                $registrant->save();
            }

            // Upload berkas baru (replace jika ada)
            $docTypes = PpdbRegistrant::DOCUMENT_TYPES;
            foreach ($docTypes as $type => $name) {
                if ($request->hasFile("doc_{$type}")) {
                    // Delete old
                    $oldDoc = $registrant->documents()->where('document_type', $type)->first();
                    if ($oldDoc) {
                        Storage::disk('public')->delete($oldDoc->file_path);
                        $oldDoc->delete();
                    }

                    $path = $request->file("doc_{$type}")->store('ppdb/documents/' . $registrant->id, 'public');
                    PpdbDocument::create([
                        'ppdb_registrant_id' => $registrant->id,
                        'document_name' => $name,
                        'document_type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data pendaftar berhasil diperbaharui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify registrant (update status + per-document verification).
     */
    public function verify(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,berkas_lengkap,berkas_tidak_lengkap,diterima,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:1000',
            'average_score' => 'nullable|numeric|min:0|max:100',
            'distance_km' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $registrant = PpdbRegistrant::findOrFail($id);

            $updateData = [
                'status' => $request->status,
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'average_score' => $request->average_score,
                'distance_km' => $request->distance_km,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ];

            // Generate nomor surat jika diterima dan belum ada nomornya
            if ($request->status === 'diterima' && !$registrant->letter_number) {
                $updateData['letter_number'] = PpdbRegistrant::generateLetterNumber();
            }

            $registrant->update($updateData);

            // Hitung selection_score
            $registrant->selection_score = $registrant->calculateSelectionScore();
            $registrant->save();

            // Update verifikasi per dokumen
            if ($request->has('doc_verified')) {
                foreach ($request->doc_verified as $docId => $isVerified) {
                    PpdbDocument::where('id', $docId)->update([
                        'is_verified' => $isVerified ? true : false,
                        'verification_note' => $request->doc_notes[$docId] ?? null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Verifikasi berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi bukti daftar ulang / pembayaran.
     */
    public function verifyReRegistration(Request $request, $id)
    {
        try {
            $registrant = PpdbRegistrant::findOrFail($id);
            
            if ($registrant->status !== PpdbRegistrant::STATUS_DAFTAR_ULANG) {
                return response()->json([
                    'status' => false,
                    'message' => 'Status pendaftar bukan Menunggu Verifikasi Pembayaran.'
                ], 422);
            }

            $registrant->update([
                'status' => PpdbRegistrant::STATUS_DAFTAR_ULANG_VERIFIED
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pembayaran berhasil divalidasi. Siswa kini siap dipindahkan ke data induk.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download berkas.
     */
    public function downloadBerkas($id)
    {
        $doc = PpdbDocument::findOrFail($id);
        $filePath = storage_path('app/public/' . $doc->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $doc->document_name . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }

    /**
     * Delete registrant.
     */
    public function destroy($id)
    {
        try {
            $registrant = PpdbRegistrant::with('documents')->findOrFail($id);

            // Delete files
            if ($registrant->foto) {
                Storage::disk('public')->delete($registrant->foto);
            }
            foreach ($registrant->documents as $doc) {
                Storage::disk('public')->delete($doc->file_path);
            }

            $registrant->delete();

            return response()->json(['message' => 'Data pendaftar berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF Letter for Registrant Status.
     */
    public function printLetter($id)
    {
        $registrant = PpdbRegistrant::with(['admissionPhase', 'admissionType'])->findOrFail($id);
        
        $setting = \App\Models\Setting::first();
        $source = \App\Models\MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);
        $phase = $registrant->admissionPhase;

        // Security Check: Student can only print their own letter and only after announcement date
        if (Auth::user()->hasRole('ppdb')) {
            if ($registrant->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }

            $isAnnouncementActive = $phase ? $phase->isAnnouncementActive() : ($admission ? $admission->isAnnouncementActive() : false);
            if (!$isAnnouncementActive) {
                abort(403, 'Pengumuman kelulusan belum dibuka. Silakan cek kembali sesuai jadwal.');
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.admission.ppdb.letter', compact('registrant', 'setting', 'source', 'admission'));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        $filename = 'SK_PPDB_' . str_replace('/', '_', $registrant->registration_number) . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Cetak Berita Acara Penerimaan Kolektif.
     */
    public function printBeritaAcara()
    {
        $source = \App\Models\MailSetting::first();
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $query = PpdbRegistrant::where('student_admission_id', $admission->id);
        
        $total_applicants = (clone $query)->count();
        $total_accepted = (clone $query)->where('status', 'diterima')->count();
        $total_rejected = (clone $query)->where('status', 'ditolak')->count();
        $total_pending = $total_applicants - $total_accepted - $total_rejected;

        $admission->generateBaLetterNumber();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.admission.ppdb.pdf.berita_acara', compact(
            'source', 'admission', 'total_applicants', 'total_accepted', 'total_rejected', 'total_pending'
        ));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');
        
        return $pdf->download('Berita_Acara_PPDB_' . date('Y') . '.pdf');
    }

    /**
     * Cetak SK Kolektif / Pengumuman Daftar Lulus.
     */
    public function printCollectiveSK()
    {
        $source = \App\Models\MailSetting::first();
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $registrants = PpdbRegistrant::where('student_admission_id', $admission->id)
            ->where('status', 'diterima')
            ->orderBy('registration_number', 'asc')
            ->get();

        $admission->generateSkLetterNumber();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.admission.ppdb.pdf.collective_sk', compact('source', 'admission', 'registrants'));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');
        
        return $pdf->download('Daftar_Lulus_PPDB_' . date('Y') . '.pdf');
    }

    public function dashboard()
    {
        $academicYear = \App\Models\AcademicYear::where('admission_semester', 1)->first();
        if (!$academicYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Summary Stats
        $stats = [
            'total' => PpdbRegistrant::count(),
            'pending' => PpdbRegistrant::where('status', 'pending')->count(),
            'verified' => PpdbRegistrant::where('status', 'berkas_lengkap')->count(),
            'accepted' => PpdbRegistrant::where('status', 'diterima')->count(),
            'rejected' => PpdbRegistrant::where('status', 'ditolak')->count(),
            'moved' => \App\Models\Student::whereNotNull('registration_number')->count(),
        ];

        // Chart Data: Registration Trend (Last 10 Days)
        $trendLabels = [];
        $trendData = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendLabels[] = date('d M', strtotime($date));
            $trendData[] = PpdbRegistrant::whereDate('created_at', $date)->count();
        }

        // Chart Data: Status Distribution
        $statusLabels = ['Pending', 'Lengkap', 'Diterima', 'Ditolak', 'Cadangan'];
        $statusData = [
            PpdbRegistrant::where('status', 'pending')->count(),
            PpdbRegistrant::where('status', 'berkas_lengkap')->count(),
            PpdbRegistrant::where('status', 'diterima')->count(),
            PpdbRegistrant::where('status', 'ditolak')->count(),
            PpdbRegistrant::where('status', 'cadangan')->count(),
        ];

        // Chart Data: Gender
        $genderData = [
            PpdbRegistrant::where('jenis_kelamin', 'L')->count(),
            PpdbRegistrant::where('jenis_kelamin', 'P')->count(),
        ];

        // Top 5 Origin Schools
        $originSchools = PpdbRegistrant::select('asal_sekolah', \DB::raw('count(*) as total'))
            ->groupBy('asal_sekolah')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        return view('admin.admission.ppdb.dashboard', compact('stats', 'trendLabels', 'trendData', 'statusLabels', 'statusData', 'genderData', 'originSchools'));
    }

    public function selection(Request $request)
    {
        $academicYear = \App\Models\AcademicYear::where('admission_semester', 1)->first();
        if (!$academicYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $phases = \App\Models\AdmissionPhase::where('academic_year_id', $academicYear->id)->get();
        $types = \App\Models\AdmissionType::where('academic_year_id', $academicYear->id)->get();

        $classGroups = \App\Models\ClassGroup::all();
        return view('admin.admission.ppdb.selection', compact('phases', 'types', 'academicYear', 'classGroups'));
    }

    public function reRegistration(Request $request)
    {
        $academicYear = \App\Models\AcademicYear::where('admission_semester', 1)->first();
        if (!$academicYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $phases = \App\Models\AdmissionPhase::where('academic_year_id', $academicYear->id)->get();
        $types = \App\Models\AdmissionType::where('academic_year_id', $academicYear->id)->get();
        $classGroups = \App\Models\ClassGroup::all();

        return view('admin.admission.ppdb.re-registration', compact('phases', 'types', 'academicYear', 'classGroups'));
    }

    public function reRegistrationData(Request $request)
    {
        $query = PpdbRegistrant::with(['admissionPhase', 'admissionType'])
            ->whereIn('status', [PpdbRegistrant::STATUS_DAFTAR_ULANG, PpdbRegistrant::STATUS_DAFTAR_ULANG_VERIFIED]);

        if ($request->phase_id) {
            $query->where('admission_phase_id', $request->phase_id);
        }
        if ($request->type_id) {
            $query->where('admission_type_id', $request->type_id);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('jk_label', fn($q) => $q->jenis_kelamin === 'L' ? 'L' : 'P')
            ->addColumn('registration_number', function ($r) {
                return '<span class="font-weight-bold text-primary">' . $r->registration_number . '</span>';
            })
            ->addColumn('confirmed_at_formatted', fn($q) => $q->confirmed_at ? $q->confirmed_at->format('d M Y, H:i') : '-')
            ->addColumn('status_badge', function ($q) {
                return '<span class="badge badge-' . $q->status_color . '">' . $q->status_label . '</span>';
            })
            ->rawColumns(['status_badge', 'registration_number'])
            ->make(true);
    }

    public function selectionData(Request $request)
    {
        $query = PpdbRegistrant::with(['admissionPhase', 'admissionType'])
            ->where('status', '!=', PpdbRegistrant::STATUS_MOVED);

        if ($request->phase_id) {
            $query->where('admission_phase_id', $request->phase_id);
        }
        if ($request->type_id) {
            $query->where('admission_type_id', $request->type_id);
        }

        $query->orderBy('selection_score', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('confirmed_at_formatted', fn($q) => $q->confirmed_at ? $q->confirmed_at->format('d M Y, H:i') : '-')
            ->addColumn('status_badge', function ($q) {
                return '<span class="badge badge-' . $q->status_color . '">' . strtoupper(str_replace('_', ' ', $q->status)) . '</span>';
            })
            ->editColumn('selection_score', fn($q) => number_format($q->selection_score, 2))
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    public function processSelection(Request $request)
    {
        $request->validate([
            'phase_id' => 'required|exists:admission_phases,id',
            'type_id' => 'required|exists:admission_types,id',
        ]);

        $quota = \App\Models\AdmissionQuotas::where('admission_phase_id', $request->phase_id)
            ->where('admission_types_id', $request->type_id)
            ->first();

        if (!$quota || $quota->quota <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Kuota untuk gelombang dan jalur ini belum diatur atau bernilai 0.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $registrants = PpdbRegistrant::where('admission_phase_id', $request->phase_id)
                ->where('admission_type_id', $request->type_id)
                ->where('status', 'berkas_lengkap')
                ->orderBy('selection_score', 'desc')
                ->get();

            $acceptedCount = 0;
            $cadanganCount = 0;
            foreach ($registrants as $registrant) {
                if ($acceptedCount < $quota->quota) {
                    $registrant->update([
                        'status' => 'diterima',
                        'letter_number' => $registrant->letter_number ?? PpdbRegistrant::generateLetterNumber()
                    ]);
                    $acceptedCount++;
                } else {
                    $registrant->update(['status' => 'cadangan']);
                    $cadanganCount++;
                }
            }

            DB::commit();

            $msg = "Proses seleksi selesai. {$acceptedCount} siswa DITERIMA";
            if ($cadanganCount > 0) $msg .= " dan {$cadanganCount} siswa menjadi CADANGAN";
            $msg .= " sesuai kuota ({$quota->quota}).";

            return response()->json([
                'status' => true,
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function moveToStudent(Request $request, $id)
    {
        $registrant = PpdbRegistrant::with('studentAdmission')->findOrFail($id);

        if ($registrant->status !== PpdbRegistrant::STATUS_DAFTAR_ULANG_VERIFIED) {
            return response()->json([
                'status' => false,
                'message' => 'Hanya siswa dengan status DAFTAR ULANG TERVERIFIKASI yang dapat dipindahkan ke data induk siswa.'
            ], 422);
        }

        $request->validate([
            'class_group_id' => 'nullable|exists:class_groups,id'
        ]);

        try {
            DB::beginTransaction();

            $student = \App\Models\Student::updateOrCreate(
                ['nisn' => $registrant->nisn],
                [
                    'nik' => $registrant->nik,
                    'nama_lengkap' => $registrant->nama_lengkap,
                    'jenis_kelamin' => $registrant->jenis_kelamin,
                    'tempat_lahir' => $registrant->tempat_lahir,
                    'tanggal_lahir' => $registrant->tanggal_lahir,
                    'academic_year_id' => $registrant->studentAdmission->academic_year_id,
                    'student_class_group_id' => $request->class_group_id ?? null,
                    'student_status_id' => 1, // Aktif
                    'asal_sekolah' => $registrant->asal_sekolah,
                    'tanggal_masuk' => $registrant->tanggal_masuk ?? now(),
                    'registration_number' => $registrant->registration_number,
                    'is_active' => true,
                ]
            );

            // Jika siswa baru dibuat, generate NIS
            if ($student->wasRecentlyCreated) {
                $student->update(['nis' => \App\Models\Student::generateNIS()]);
            }

            \App\Models\StudentParent::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'father_name' => $registrant->nama_ayah,
                    'mother_name' => $registrant->nama_ibu,
                    'father_phone' => $registrant->no_hp_ortu,
                ]
            );

            \App\Models\StudentProfile::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'nik' => $registrant->nik,
                    'alamat' => $registrant->alamat,
                    'foto' => $registrant->foto,
                ]
            );

            $registrant->update(['status' => PpdbRegistrant::STATUS_MOVED]);

            DB::commit();

            $msg = $student->wasRecentlyCreated ? "{$registrant->nama_lengkap} berhasil dipindahkan ke data induk siswa." : "Data induk {$registrant->nama_lengkap} berhasil diperbarui dari data PPDB.";

            return response()->json([
                'status' => true,
                'message' => $msg
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkMoveToStudent(Request $request)
    {
        $request->validate([
            'phase_id' => 'required|exists:admission_phases,id',
            'type_id' => 'required|exists:admission_types,id',
            // class_group_id dihapus dari required
        ]);

        $registrants = PpdbRegistrant::where('admission_phase_id', $request->phase_id)
            ->where('admission_type_id', $request->type_id)
            ->where('status', PpdbRegistrant::STATUS_DAFTAR_ULANG_VERIFIED)
            ->get();

        if ($registrants->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada pendaftar dengan status DITERIMA atau DAFTAR ULANG pada gelombang dan jalur ini.'
            ], 422);
        }

        $count = 0;
        $errors = 0;

        foreach ($registrants as $registrant) {
            try {
                DB::beginTransaction();

                $student = \App\Models\Student::updateOrCreate(
                    ['nisn' => $registrant->nisn],
                    [
                        'nik' => $registrant->nik,
                        'nama_lengkap' => $registrant->nama_lengkap,
                        'jenis_kelamin' => $registrant->jenis_kelamin,
                        'tempat_lahir' => $registrant->tempat_lahir,
                        'tanggal_lahir' => $registrant->tanggal_lahir,
                        'academic_year_id' => $registrant->studentAdmission->academic_year_id,
                        'student_class_group_id' => $request->class_group_id ?? null,
                        'student_status_id' => 1, // Aktif
                        'asal_sekolah' => $registrant->asal_sekolah,
                        'tanggal_masuk' => $registrant->tanggal_masuk ?? now(),
                        'registration_number' => $registrant->registration_number,
                        'is_active' => true,
                    ]
                );

                if ($student->wasRecentlyCreated) {
                    $student->update(['nis' => \App\Models\Student::generateNIS()]);
                }

                \App\Models\StudentParent::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'father_name' => $registrant->nama_ayah,
                        'mother_name' => $registrant->nama_ibu,
                        'father_phone' => $registrant->no_hp_ortu,
                    ]
                );

                \App\Models\StudentProfile::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'nik' => $registrant->nik,
                        'alamat' => $registrant->alamat,
                        'foto' => $registrant->foto,
                    ]
                );

                $registrant->update(['status' => PpdbRegistrant::STATUS_MOVED]);

                DB::commit();
                $count++;
            } catch (\Exception $e) {
                DB::rollBack();
                $errors++;
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Berhasil memindahkan {$count} siswa. ({$errors} data dilewati/gagal)."
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|string|in:pending,berkas_lengkap,berkas_tidak_lengkap,diterima,ditolak',
        ]);

        try {
            DB::beginTransaction();

            $registrants = PpdbRegistrant::whereIn('id', $request->ids)->get();
            $count = 0;

            foreach ($registrants as $registrant) {
                $registrant->update([
                    'status' => $request->status,
                    'letter_number' => ($request->status == 'diterima' && !$registrant->letter_number) 
                        ? PpdbRegistrant::generateLetterNumber() 
                        : $registrant->letter_number
                ]);
                $count++;
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "{$count} pendaftar berhasil diperbarui statusnya menjadi " . strtoupper($request->status)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
