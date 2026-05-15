<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MutabaahLog;
use App\Models\Student;
use App\Models\TahfidzLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SiswaMutabaahController extends Controller
{
    /**
     * Dashboard ibadah siswa — read-only (input oleh guru/admin).
     */
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $today = Carbon::today();
        $todayLog = MutabaahLog::where('student_id', $student->id)
            ->whereDate('date', $today)->first();

        // Kalender 30 hari terakhir
        $startDate = $today->copy()->subDays(29);
        $calendarLogs = MutabaahLog::where('student_id', $student->id)
            ->whereBetween('date', [$startDate, $today])
            ->get()
            ->keyBy(fn($log) => Carbon::parse($log->date)->format('Y-m-d'));

        $calendarDays = [];
        for ($d = $startDate->copy(); $d->lte($today); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $log = $calendarLogs->get($key);
            $score = 0;
            if ($log) {
                $score = $log->shubuh + $log->zhuhur + $log->ashar + $log->maghrib + $log->isya + $log->dhuha + $log->tahajud;
            }
            $calendarDays[] = [
                'date'  => $key,
                'day'   => $d->format('d'),
                'label' => $d->translatedFormat('D'),
                'score' => $score,
                'max'   => 7,
                'is_today' => $d->isToday(),
            ];
        }

        // Statistik bulanan
        $monthLogs = MutabaahLog::where('student_id', $student->id)
            ->whereMonth('date', $today->month)
            ->whereYear('date', $today->year)->get();

        $totalShalat = $monthLogs->sum(fn($l) => $l->shubuh + $l->zhuhur + $l->ashar + $l->maghrib + $l->isya);
        $totalDays = $monthLogs->count();
        $maxShalat = $totalDays * 5;
        $percentage = $maxShalat > 0 ? round(($totalShalat / $maxShalat) * 100) : 0;

        // Tahfidz progress
        $tahfidzLogs = TahfidzLog::where('student_id', $student->id)
            ->orderByDesc('date')
            ->limit(20)->get();

        $totalSurah = TahfidzLog::where('student_id', $student->id)
            ->where('type', 'ziyadah')
            ->distinct('surah_name')
            ->count('surah_name');

        return view('siswa.mutabaah.index', compact(
            'student', 'todayLog', 'calendarDays',
            'totalShalat', 'maxShalat', 'percentage', 'totalDays',
            'tahfidzLogs', 'totalSurah'
        ));
    }
}
