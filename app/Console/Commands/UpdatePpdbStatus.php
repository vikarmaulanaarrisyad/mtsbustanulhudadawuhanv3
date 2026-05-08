<?php

namespace App\Console\Commands;

use App\Models\AdmissionPhase;
use App\Models\PpdbRegistrant;
use App\Models\StudentAdmission;
use Illuminate\Console\Command;

class UpdatePpdbStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis membuka/menutup PPDB dan melakukan seleksi siswa berdasarkan kuota';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $this->info('Memulai update status PPDB: ' . $now->toDateTimeString());

        // 1. Update Status Pembukaan PPDB (Open/Close)
        $admissions = StudentAdmission::all();
        foreach ($admissions as $adm) {
            $shouldBeOpen = $now->between($adm->admission_start_date, $adm->admission_end_date->endOfDay());
            $status = $shouldBeOpen ? 'open' : 'close';
            
            if ($adm->admission_status !== $status) {
                $adm->admission_status = $status;
                $adm->save();
                $this->line("PPDB [{$adm->academicYear->year_name}] status diubah menjadi: " . strtoupper($status));
            }
        }

        // 2. Seleksi Otomatis berdasarkan Pengumuman & Quota
        $activePhases = AdmissionPhase::where('announcement_date', '<=', $now)->get();
        $syncedCount = 0;

        foreach ($activePhases as $phase) {
            // Ambil pendaftar yang statusnya belum final (masih pending atau berkas lengkap)
            $registrants = PpdbRegistrant::where('admission_phase_id', $phase->id)
                ->whereIn('status', [
                    PpdbRegistrant::STATUS_PENDING,
                    PpdbRegistrant::STATUS_BERKAS_LENGKAP
                ])
                ->get();

            foreach ($registrants as $reg) {
                $oldStatus = $reg->status;
                $reg->syncStatus(); // Menentukan DITERIMA atau CADANGAN berdasarkan ranking & kuota
                
                if ($oldStatus !== $reg->status) {
                    $syncedCount++;
                }
            }
        }

        $this->info("Selesai. Total siswa berhasil diseleksi otomatis: {$syncedCount}");
    }
}
