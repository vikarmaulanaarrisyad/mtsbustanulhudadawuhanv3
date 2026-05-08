<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\PpdbRegistrant;
use App\Models\AdmissionPhase;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule Otomatis Update Status PPDB & Seleksi Kuota
Schedule::command('ppdb:update-status')->everyFiveMinutes();

// Schedule Otomatis Update Status CBT (Aktif/Nonaktif)
Schedule::command('cbt:update-status')->everyMinute();
