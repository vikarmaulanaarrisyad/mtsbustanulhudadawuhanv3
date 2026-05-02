<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistrant;
use App\Models\PpdbDocument;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AcademicYear;
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

        return view('admin.admission.ppdb.index', compact(
            'academicYear',
            'admission',
            'phases',
            'types',
            'stats'
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
            ->when($request->phase_id, fn($q) => $q->where('admission_phase_id', $request->phase_id))
            ->when($request->type_id, fn($q) => $q->where('admission_type_id', $request->type_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('gelombang', fn($r) => $r->admissionPhase->phase_name ?? '-')
            ->addColumn('jalur', fn($r) => $r->admissionType->admission_type_name ?? '-')
            ->addColumn('jk_label', fn($r) => $r->jenis_kelamin === 'L' ? 'L' : 'P')
            ->addColumn('status_badge', function ($r) {
                return '<span class="badge badge-' . $r->status_color . '">' . $r->status_label . '</span>';
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(' . $r->id . ')" class="btn btn-xs btn-info" title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="openVerify(' . $r->id . ')" class="btn btn-xs btn-success" title="Verifikasi">
                        <i class="fas fa-clipboard-check"></i>
                    </button>
                    <button onclick="editForm(`' . route('ppdb.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('ppdb.destroy', $r->id) . '`, `' . $r->nama_lengkap . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
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
                'status' => $request->status,
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

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
}
