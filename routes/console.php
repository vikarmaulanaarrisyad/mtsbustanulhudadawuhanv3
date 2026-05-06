<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\PpdbRegistrant;
use App\Models\AdmissionPhase;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/**
 * Scedule Otomatis untuk Sinkronisasi Status Kelulusan PPDB
 * Berjalan setiap 5 menit untuk memastikan data ranking & kuota selalu sinkron ke database
 */
Schedule::call(function () {
    // Ambil semua gelombang yang tanggal pengumumannya sudah lewat atau hari ini
    $activePhases = AdmissionPhase::where('announcement_date', '<=', now())->get();

    foreach ($activePhases as $phase) {
        // Ambil pendaftar yang statusnya belum final (masih pending atau berkas lengkap)
        $registrants = PpdbRegistrant::where('admission_phase_id', $phase->id)
            ->whereIn('status', [
                PpdbRegistrant::STATUS_PENDING,
                PpdbRegistrant::STATUS_BERKAS_LENGKAP
            ])
            ->get();

        foreach ($registrants as $reg) {
            // Jalankan fungsi sinkronisasi otomatis
            $reg->syncStatus();
        }
    }
})->everyFiveMinutes();
