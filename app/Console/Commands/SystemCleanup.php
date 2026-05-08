<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SystemCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sys:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membersihkan file sampah, log lama, dan cache aplikasi secara otomatis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pembersihan sistem: ' . now()->toDateTimeString());

        // 1. Hapus Log yang lebih dari 30 hari
        $this->cleanupLogs();

        // 2. Hapus file temporary Excel / ZIP di storage/app
        $this->cleanupTempFiles();

        // 3. Bersihkan Cache Aplikasi
        $this->clearAppCache();

        $this->info('Pembersihan sistem selesai!');
    }

    private function cleanupLogs()
    {
        $this->line('Membersihkan file log lama...');
        $logPath = storage_path('logs');
        $files = File::files($logPath);
        $count = 0;

        foreach ($files as $file) {
            // Jika file berakhiran .log dan umurnya > 30 hari
            if ($file->getExtension() === 'log') {
                $lastModified = filemtime($file->getPathname());
                if (time() - $lastModified > (30 * 24 * 60 * 60)) {
                    File::delete($file->getPathname());
                    $count++;
                }
            }
        }
        $this->line("- Berhasil menghapus {$count} file log lama.");
    }

    private function cleanupTempFiles()
    {
        $this->line('Membersihkan file temporary (Excel/ZIP)...');
        $count = 0;

        // Target file zip backup lama atau file excel temp
        $files = File::files(storage_path('app'));
        foreach ($files as $file) {
            $ext = $file->getExtension();
            if (in_array($ext, ['zip', 'xlsx', 'csv'])) {
                // Hapus jika lebih dari 7 hari
                if (time() - filemtime($file->getPathname()) > (7 * 24 * 60 * 60)) {
                    File::delete($file->getPathname());
                    $count++;
                }
            }
        }
        
        // Hapus folder laravel-excel jika ada
        if (File::exists(storage_path('framework/laravel-excel'))) {
            File::cleanDirectory(storage_path('framework/laravel-excel'));
        }

        $this->line("- Berhasil menghapus {$count} file temporary.");
    }

    private function clearAppCache()
    {
        $this->line('Membersihkan cache aplikasi...');
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            $this->line('- Cache, View, dan Config berhasil dibersihkan.');
        } catch (\Exception $e) {
            $this->error('- Gagal membersihkan cache: ' . $e->getMessage());
        }
    }
}
