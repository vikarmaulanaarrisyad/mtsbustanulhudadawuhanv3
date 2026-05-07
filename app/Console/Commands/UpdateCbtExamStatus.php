<?php

namespace App\Console\Commands;

use App\Models\CbtExam;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCbtExamStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cbt:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengaktifkan dan menonaktifkan jadwal ujian CBT berdasarkan waktu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $this->info('Memulai update status ujian CBT: ' . $now->toDateTimeString());

        $exams = CbtExam::all();
        $activated = 0;
        $deactivated = 0;

        foreach ($exams as $exam) {
            // Gabungkan tanggal dan jam
            $start = Carbon::parse($exam->exam_date->format('Y-m-d') . ' ' . $exam->start_time);
            $end = Carbon::parse($exam->exam_date->format('Y-m-d') . ' ' . $exam->end_time);

            $shouldBeActive = $now->between($start, $end);

            if ($exam->is_active != $shouldBeActive) {
                $exam->is_active = $shouldBeActive;
                $exam->save();

                if ($shouldBeActive) {
                    $activated++;
                    $this->line("Ujian [{$exam->name}] DIAKTIFKAN.");
                } else {
                    $deactivated++;
                    $this->line("Ujian [{$exam->name}] DINONAKTIFKAN.");
                }
            }
        }

        $this->info("Selesai. Diaktifkan: {$activated}, Dinonaktifkan: {$deactivated}");
    }
}
