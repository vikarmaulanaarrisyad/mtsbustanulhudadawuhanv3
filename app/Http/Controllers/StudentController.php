<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\StudentParent;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\StudentStatus;
use App\Models\Residence;
use App\Models\Education;
use App\Models\MonthlyIncome;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        $studentStatuses = StudentStatus::all();
        $residences = Residence::all();
        $educations = Education::all();
        $monthlyIncomes = MonthlyIncome::all();

        $totalStudents = Student::count();
        $activeStudents = Student::where('is_active', true)->count();
        $male = Student::where('jenis_kelamin', 'L')->count();
        $female = Student::where('jenis_kelamin', 'P')->count();

        return view('admin.academic.students.index', compact(
            'academicYears',
            'classGroups',
            'studentStatuses',
            'residences',
            'educations',
            'monthlyIncomes',
            'totalStudents',
            'activeStudents',
            'male',
            'female'
        ));
    }

    /**
     * DataTables server-side data.
     */
    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear', 'studentStatus'])
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->when($request->class_group_id, fn($q) => $q->where('student_class_group_id', $request->class_group_id))
            ->when($request->status_id, fn($q) => $q->where('student_status_id', $request->status_id))
            ->when($request->jenis_kelamin, fn($q) => $q->where('jenis_kelamin', $request->jenis_kelamin))
            ->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kelas', function ($student) {
                return $student->kelas_lengkap;
            })
            ->addColumn('tahun_akademik', function ($student) {
                return $student->academicYear->academic_year ?? '-';
            })
            ->addColumn('status', function ($student) {
                if (!$student->studentStatus) return '<span class="badge badge-secondary">-</span>';
                $color = $student->is_active ? 'success' : 'danger';
                return '<span class="badge badge-' . $color . '">' . $student->studentStatus->student_status_name . '</span>';
            })
            ->addColumn('jk_badge', function ($student) {
                $color = $student->jenis_kelamin === 'L' ? 'info' : 'danger';
                $label = $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                return '<span class="badge badge-' . $color . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($student) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(' . $student->id . ')" class="btn btn-xs btn-info" title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="editForm(`' . route('students.show', $student->id) . '`)" class="btn btn-xs" style="background-color:#6755a5; color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('students.destroy', $student->id) . '`, `' . $student->nama_lengkap . '`)" class="btn btn-xs" style="background-color:#d81b60; color:#fff;" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->addColumn('select_checkbox', function ($student) {
                return '<input type="checkbox" class="select-row" value="' . $student->id . '">';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|unique:students,nis|max:30',
            'nisn' => 'nullable|unique:students,nisn|max:30',
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'student_class_group_id' => 'nullable|exists:class_groups,id',
            'student_status_id' => 'nullable|exists:student_statuses,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali data Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Store student
            $student = Student::create([
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'student_residence_id' => $request->student_residence_id,
                'anak_ke' => $request->anak_ke,
                'jumlah_saudara' => $request->jumlah_saudara,
                'academic_year_id' => $request->academic_year_id,
                'student_status_id' => $request->student_status_id,
                'student_class_group_id' => $request->student_class_group_id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'asal_sekolah' => $request->asal_sekolah,
                'no_ijazah' => $request->no_ijazah,
                'is_active' => $request->has('is_active') ? true : true,
                'keterangan' => $request->keterangan,
            ]);

            // Store profile
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('students/foto', 'public');
            }

            StudentProfile::create([
                'student_id' => $student->id,
                'nik' => $request->profile_nik,
                'no_kk' => $request->profile_no_kk,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'kabupaten' => $request->kabupaten,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'transportasi' => $request->transportasi,
                'jarak_rumah' => $request->jarak_rumah,
                'tinggi_badan' => $request->tinggi_badan,
                'berat_badan' => $request->berat_badan,
                'golongan_darah' => $request->golongan_darah,
                'foto' => $fotoPath,
            ]);

            // Store parents
            StudentParent::create([
                'student_id' => $student->id,
                'father_name' => $request->father_name,
                'father_nik' => $request->father_nik,
                'father_education_id' => $request->father_education_id,
                'father_income_id' => $request->father_income_id,
                'father_phone' => $request->father_phone,
                'mother_name' => $request->mother_name,
                'mother_nik' => $request->mother_nik,
                'mother_education_id' => $request->mother_education_id,
                'mother_income_id' => $request->mother_income_id,
                'mother_phone' => $request->mother_phone,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data siswa berhasil ditambahkan.',
                'data' => $student
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
     * Display the specified resource (JSON for edit form).
     */
    public function show($id)
    {
        $student = Student::with(['profile', 'parents', 'classGroup', 'academicYear', 'studentStatus', 'residence'])->findOrFail($id);

        return response()->json(['data' => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|max:30|unique:students,nis,' . $id,
            'nisn' => 'nullable|max:30|unique:students,nisn,' . $id,
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'student_class_group_id' => 'nullable|exists:class_groups,id',
            'student_status_id' => 'nullable|exists:student_statuses,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali data Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);

            $student->update([
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'no_kk' => $request->no_kk,
                'nama_lengkap' => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'student_residence_id' => $request->student_residence_id,
                'anak_ke' => $request->anak_ke,
                'jumlah_saudara' => $request->jumlah_saudara,
                'academic_year_id' => $request->academic_year_id,
                'student_status_id' => $request->student_status_id,
                'student_class_group_id' => $request->student_class_group_id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_keluar' => $request->tanggal_keluar,
                'asal_sekolah' => $request->asal_sekolah,
                'no_ijazah' => $request->no_ijazah,
                'is_active' => $request->has('is_active') ? true : false,
                'keterangan' => $request->keterangan,
            ]);

            // Update profile
            $profile = $student->profile ?? new StudentProfile(['student_id' => $student->id]);

            if ($request->hasFile('foto')) {
                // Delete old foto
                if ($profile->foto) {
                    Storage::disk('public')->delete($profile->foto);
                }
                $profile->foto = $request->file('foto')->store('students/foto', 'public');
            }

            $profile->fill([
                'student_id' => $student->id,
                'nik' => $request->profile_nik,
                'no_kk' => $request->profile_no_kk,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'desa' => $request->desa,
                'kecamatan' => $request->kecamatan,
                'kabupaten' => $request->kabupaten,
                'provinsi' => $request->provinsi,
                'kode_pos' => $request->kode_pos,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'transportasi' => $request->transportasi,
                'jarak_rumah' => $request->jarak_rumah,
                'tinggi_badan' => $request->tinggi_badan,
                'berat_badan' => $request->berat_badan,
                'golongan_darah' => $request->golongan_darah,
            ]);
            $profile->save();

            // Update parents
            $parents = $student->parents ?? new StudentParent(['student_id' => $student->id]);
            $parents->fill([
                'student_id' => $student->id,
                'father_name' => $request->father_name,
                'father_nik' => $request->father_nik,
                'father_education_id' => $request->father_education_id,
                'father_income_id' => $request->father_income_id,
                'father_phone' => $request->father_phone,
                'mother_name' => $request->mother_name,
                'mother_nik' => $request->mother_nik,
                'mother_education_id' => $request->mother_education_id,
                'mother_income_id' => $request->mother_income_id,
                'mother_phone' => $request->mother_phone,
            ]);
            $parents->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data siswa berhasil diperbaharui.',
                'data' => $student
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);

            // Delete foto if exists
            if ($student->profile && $student->profile->foto) {
                Storage::disk('public')->delete($student->profile->foto);
            }

            $student->delete();

            return response()->json(['message' => 'Data siswa berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete selected students.
     */
    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->ids;
            if (empty($ids)) {
                return response()->json(['status' => false, 'message' => 'Tidak ada data yang dipilih.'], 400);
            }

            $students = Student::with('profile')->whereIn('id', $ids)->get();

            foreach ($students as $student) {
                if ($student->profile && $student->profile->foto) {
                    Storage::disk('public')->delete($student->profile->foto);
                }
                $student->delete();
            }

            return response()->json(['status' => true, 'message' => count($ids) . ' data siswa berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import from Excel.
     */
    public function importEXCEL(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Excel::import(new StudentImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil diimport!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download Excel template.
     */
    public function downloadTemplate()
    {
        $headers = [
            'nis', 'nisn', 'nik', 'no_kk',
            'nama_lengkap', 'nama_panggilan', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir',
            'anak_ke', 'jumlah_saudara',
            'kelas', 'tahun_pelajaran', 'status_siswa',
            'tanggal_masuk', 'asal_sekolah',
            'alamat', 'rt', 'rw', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos',
            'no_hp',
            'nama_ayah', 'hp_ayah',
            'nama_ibu', 'hp_ibu',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Siswa');

        // Write headers
        foreach ($headers as $colIndex => $header) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue($col . '1', $header);

            // Style header
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            $sheet->getStyle($col . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Example row
        $example = [
            '12345', '0012345678', '3201234567890001', '3201234567890000',
            'Ahmad Fauzi', 'Fauzi', 'L',
            'Dawuhan', '2012-05-15',
            '1', '2',
            'Kelas 7 A', '2025/2026', 'Aktif',
            '2025-07-14', 'SDN 1 Dawuhan',
            'Jl. Merdeka No. 10', '001', '002', 'Dawuhan', 'Situbondo', 'Situbondo', 'Jawa Timur', '68351',
            '081234567890',
            'Budi Santoso', '081234567891',
            'Siti Aminah', '081234567892',
        ];

        foreach ($example as $colIndex => $value) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue($col . '2', $value);
            $sheet->getStyle($col . '2')->getFont()->setItalic(true);
            $sheet->getStyle($col . '2')->getFont()->getColor()->setARGB('FF999999');
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $fileName = 'template_data_siswa.xlsx';
        $tempPath = storage_path('app/' . $fileName);
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
