<?php
 
namespace App\Http\Controllers\Siswa;
 
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\ClassSchedule;
use App\Models\SchoolAgenda;
use App\Models\Announcement;
use App\Models\StudentPermit;
use App\Models\MutabaahLog;
use App\Models\TahfidzLog;
use App\Models\AttendanceSetting;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 
class SiswaDashboardController extends Controller
{
    public function index()
    {
        $student = $this->getStudent();
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Profil Siswa tidak ditemukan. Mohon hubungi Administrator.');
        }

        // 1. Jadwal Pelajaran (Hari Ini)
        $today = now()->dayOfWeekIso;
        $todaySchedules = ClassSchedule::where('class_group_id', $student->student_class_group_id)
            ->where('day', $today)
            ->with(['subject', 'teacher', 'studyPeriod'])
            ->orderBy('start_time')
            ->get();

        // 2. Statistik Presensi
        $attendanceStats = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0];
        $attendances = StudentAttendance::where('student_id', $student->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $attendanceStats['H'] = $attendances['present'] ?? 0;
        $attendanceStats['I'] = $attendances['permit'] ?? 0;
        $attendanceStats['S'] = $attendances['sick'] ?? 0;
        $attendanceStats['A'] = $attendances['absent'] ?? 0;

        // 3. Agenda Sekolah
        $agendas = SchoolAgenda::where('start_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        // 4. Status Absen Hari Ini
        $todayAttendance = StudentAttendance::where('student_id', $student->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();
        $hasCheckedInToday = $todayAttendance ? true : false;

        // 5. Mutaba'ah & Tahfidz
        $todayMutabaah = MutabaahLog::where('student_id', $student->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();
        $tahfidzLogs = TahfidzLog::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        // 6. Poin Karakter
        $behaviorLogs = $student->behaviorLogs()->with('teacher')->orderBy('date', 'desc')->take(5)->get();
        $totalPositivePoints = $student->behaviorLogs()->where('type', 'positive')->sum('points');
        $totalNegativePoints = $student->behaviorLogs()->where('type', 'negative')->sum('points');
        $netPoints = $totalPositivePoints - $totalNegativePoints;

        $announcements = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Siswa'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // 8. Tabungan Siswa
        $savings = \App\Models\StudentSaving::where('student_id', $student->id)->first();
        $recentSavingsTransactions = collect([]);
        if ($savings) {
            $recentSavingsTransactions = \App\Models\StudentSavingTransaction::where('student_saving_id', $savings->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // 8. Validasi Aturan Absensi
        $attendanceSetting = AttendanceSetting::first();
        $isWorkDay = true;
        $isCheckInTime = true;
        $isHoliday = false;
        $attendanceMessage = "";

        if ($attendanceSetting) {
            $now = now();
            $isWorkDay = in_array($now->dayOfWeekIso, (array) $attendanceSetting->work_days);
            $isCheckInTime = $now->between($attendanceSetting->check_in_start, $attendanceSetting->check_in_end);
            $isHoliday = Holiday::where('holiday_date', $now->format('Y-m-d'))->exists();

            if (!$isWorkDay) $attendanceMessage = "Hari ini bukan hari sekolah.";
            elseif ($isHoliday) $attendanceMessage = "Hari ini adalah hari libur sekolah.";
            elseif (!$isCheckInTime) {
                if ($now->lessThan($attendanceSetting->check_in_start)) {
                    $attendanceMessage = "Absen dibuka jam " . substr($attendanceSetting->check_in_start, 0, 5);
                } else {
                    $attendanceMessage = "Waktu absen masuk sudah berakhir.";
                }
            }
        }

        return view('siswa.dashboard.index', compact(
            'student', 'todaySchedules', 'attendanceStats', 'agendas', 
            'hasCheckedInToday', 'announcements', 'isWorkDay', 'isCheckInTime', 
            'isHoliday', 'attendanceMessage', 'todayAttendance',
            'todayMutabaah', 'tahfidzLogs', 'behaviorLogs', 'totalPositivePoints', 
            'totalNegativePoints', 'netPoints', 'savings', 'recentSavingsTransactions'
        ));
    }

    /**
     * Lihat Nilai / Rapor Siswa.
     */
    public function raport()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $grades = \App\Models\StudentGrade::where('student_id', $student->id)
            ->with(['subject', 'academicYear'])
            ->get()
            ->groupBy('academic_year_id');

        return view('siswa.raport.index', compact('student', 'grades'));
    }

    /**
     * Lihat Hasil Ujian CBT.
     */
    public function cbtResults()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $examResults = \App\Models\CbtStudentExam::where('student_id', $student->id)
            ->where('status', 'finished')
            ->with(['exam.bank'])
            ->orderBy('end_time', 'desc')
            ->get();

        return view('siswa.cbt.results', compact('student', 'examResults'));
    }

    /**
     * Lihat Jadwal Pelajaran Lengkap.
     */
    public function schedule()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $schedules = ClassSchedule::where('class_group_id', $student->student_class_group_id)
            ->with(['subject', 'teacher', 'studyPeriod'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('siswa.schedule.index', compact('student', 'schedules'));
    }

    /**
     * Lihat Semua Pengumuman.
     */
    public function announcements()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $announcements = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Siswa'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.announcements.index', compact('student', 'announcements'));
    }

    /**
     * Riwayat Izin Siswa.
     */
    public function permits()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $permits = StudentPermit::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.permits.index', compact('student', 'permits'));
    }

    private function getStudent()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)
            ->with(['classGroup.homeroomTeacher', 'academicYear'])
            ->first();

        if (!$student && $user->ppdbRegistrant) {
            $student = Student::where('nisn', $user->ppdbRegistrant->nisn)
                ->with(['classGroup.homeroomTeacher', 'academicYear'])
                ->first();
        }

        return $student;
    }

    /**
     * Absensi Mandiri Siswa.
     */
    public function storeAttendance(Request $request)
    {
        $student = $this->getStudent();
        if (!$student) return response()->json(['message' => 'Data siswa tidak ditemukan.'], 404);

        // Cek Aturan Absensi Global
        $setting = AttendanceSetting::first();
        if ($setting) {
            $now = now();
            if (!in_array($now->dayOfWeekIso, (array) $setting->work_days)) {
                return response()->json(['message' => 'Hari ini bukan hari sekolah.'], 422);
            }
            if (!$now->between($setting->check_in_start, $setting->check_in_end)) {
                return response()->json(['message' => 'Waktu absen sudah ditutup atau belum dibuka.'], 422);
            }
            if (Holiday::where('holiday_date', $now->format('Y-m-d'))->exists()) {
                return response()->json(['message' => 'Hari ini adalah hari libur.'], 422);
            }
        }

        // Cek sudah absen hari ini
        $exists = StudentAttendance::where('student_id', $student->id)
            ->where('date', now()->format('Y-m-d'))
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Anda sudah melakukan absensi hari ini.'], 422);
        }

        try {
            StudentAttendance::create([
                'student_id' => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id' => $student->student_class_group_id,
                'date' => now()->format('Y-m-d'),
                'time' => now()->format('H:i:s'),
                'status' => 'present',
                'notes' => 'Absensi Mandiri Dashboard',
            ]);

            return response()->json(['message' => 'Absensi berhasil! Selamat belajar.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal melakukan absensi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Pengajuan Izin Siswa.
     */
    public function storePermit(Request $request)
    {
        $student = $this->getStudent();
        if (!$student) return response()->json(['message' => 'Data siswa tidak ditemukan.'], 404);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Izin,Sakit',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah sudah ada izin di rentang tanggal yang sama
        $startDate = $request->start_date;
        $endDate = $request->end_date ?? $startDate;

        $exists = StudentPermit::where('student_id', $student->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda sudah memiliki pengajuan izin/sakit yang terdaftar pada rentang tanggal tersebut.'
            ], 422);
        }

        try {
            $permit = new StudentPermit();
            $permit->student_id = $student->id;
            $permit->type = $request->type;
            $permit->start_date = $request->start_date;
            $permit->end_date = $request->end_date;
            $permit->reason = $request->reason;
            $permit->status = 'pending';

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('permits/students', 'public');
                $permit->attachment = $path;
            }

            $permit->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan Jurnal Ibadah (Mutaba'ah Yaumiyah).
     */
    public function storeMutabaah(Request $request)
    {
        $student = $this->getStudent();
        if (!$student) return response()->json(['message' => 'Data siswa tidak ditemukan.'], 404);

        $data = $request->only([
            'shubuh', 'zhuhur', 'ashar', 'maghrib', 'isya', 
            'dhuha', 'tahajud', 'puasa', 'tadarus'
        ]);

        // Convert string checkboxes to boolean
        $boolFields = ['shubuh', 'zhuhur', 'ashar', 'maghrib', 'isya', 'dhuha', 'tahajud'];
        foreach ($boolFields as $field) {
            $data[$field] = $request->has($field) || $request->input($field) == '1';
        }

        try {
            MutabaahLog::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'date' => now()->format('Y-m-d')
                ],
                $data
            );

            return response()->json(['success' => true, 'message' => 'Jurnal Ibadah berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
