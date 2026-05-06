<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupMail;
use Carbon\Carbon;
use App\Models\Setting;

class BackupController extends Controller
{
    private function applyConfig()
    {
        $setting = Setting::first();
        if ($setting && $setting->google_drive_folder_id) {
            $jsonPath = storage_path('app/' . ($setting->google_drive_json ?? 'google-drive-key.json'));
            
            config([
                'filesystems.disks.google.folderId' => $setting->google_drive_folder_id,
                'filesystems.disks.google.serviceAccountJson' => $jsonPath,
            ]);
        }
    }

    public function index()
    {
        $setting = Setting::first();
        
        // Gunakan native PHP glob untuk bypass masalah pembacaan Flysystem di Windows
        $files = glob(storage_path('app/*.zip'));
        
        $backups = [];
        if ($files) {
            foreach ($files as $file) {
                $backups[] = [
                    'file_path' => basename($file), // nama file saja untuk keperluan hapus/download
                    'file_name' => basename($file),
                    'file_size' => $this->formatBytes(filesize($file)),
                    'last_modified' => Carbon::createFromTimestamp(filemtime($file))->diffForHumans(),
                    'raw_timestamp' => filemtime($file)
                ];
            }
        }

        // Sort by newest
        usort($backups, function($a, $b) {
            return $b['raw_timestamp'] <=> $a['raw_timestamp'];
        });

        return view('admin.backup.index', compact('backups', 'setting'));
    }

    public function create(Request $request)
    {
        try {
            $mysqlPath = 'C:\\xampp\\mysql\\bin';
            putenv("PATH={$mysqlPath};" . getenv('PATH'));
            
            $phpBinary = PHP_BINARY;
            $artisan = base_path('artisan');
            exec("\"$phpBinary\" \"$artisan\" backup:run --only-db --only-to-disk=local 2>&1", $output, $exitCode);
            
            if ($exitCode !== 0) {
                $outStr = implode("\n", $output);
                throw new \Exception("Proses backup gagal (Exit Code: $exitCode). Output: " . substr($outStr, 0, 300));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Backup database berhasil disimpan di server lokal!'
            ]);
        } catch (\Exception $e) {
            Log::error('Backup DB failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createFull(Request $request)
    {
        try {
            $mysqlPath = 'C:\\xampp\\mysql\\bin';
            putenv("PATH={$mysqlPath};" . getenv('PATH'));
            
            $phpBinary = PHP_BINARY;
            $artisan = base_path('artisan');
            exec("\"$phpBinary\" \"$artisan\" backup:run --only-to-disk=local 2>&1", $output, $exitCode);
            
            if ($exitCode !== 0) {
                $outStr = implode("\n", $output);
                throw new \Exception("Proses backup gagal (Exit Code: $exitCode). Output: " . substr($outStr, 0, 300));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Backup lengkap berhasil disimpan di server lokal!'
            ]);
        } catch (\Exception $e) {
            Log::error('Full backup failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }



    public function restore($fileName)
    {
        try {
            $backupFolderName = '';
            $filePath = storage_path('app/' . $fileName);
            
            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            // Simple restoration logic:
            // 1. Unzip
            // 2. Find SQL file
            // 3. Import SQL
            
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === TRUE) {
                $tempPath = storage_path('app/temp-restore-' . time());
                if (!is_dir($tempPath)) mkdir($tempPath, 0755, true);
                
                $zip->extractTo($tempPath);
                $zip->close();

                // Find .sql file inside db-dumps folder (standard spatie structure)
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempPath));
                $sqlFile = null;
                foreach ($files as $file) {
                    if ($file->isFile() && $file->getExtension() == 'sql') {
                        $sqlFile = $file->getRealPath();
                        break;
                    }
                }

                if ($sqlFile) {
                    $dbConfig = config('database.connections.' . config('database.default'));
                    
                    // Import command (MySQL)
                    $command = sprintf(
                        'mysql -u%s -p%s %s < %s',
                        escapeshellarg($dbConfig['username']),
                        escapeshellarg($dbConfig['password']),
                        escapeshellarg($dbConfig['database']),
                        escapeshellarg($sqlFile)
                    );
                    
                    exec($command);
                    
                    // Cleanup
                    $this->deleteDir($tempPath);
                    
                    return back()->with('success', 'Database berhasil dipulihkan!');
                }

                $this->deleteDir($tempPath);
                return back()->with('error', 'File SQL tidak ditemukan di dalam ZIP.');
            }

            return back()->with('error', 'Gagal membuka file ZIP.');
        } catch (\Exception $e) {
            Log::error('Restore failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function deleteDir($dirPath) {
        if (!is_dir($dirPath)) return;
        $files = array_diff(scandir($dirPath), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dirPath/$file")) ? $this->deleteDir("$dirPath/$file") : unlink("$dirPath/$file");
        }
        return rmdir($dirPath);
    }

    public function download($fileName)
    {
        $backupFolderName = '';
        $file = $fileName;
        $disk = Storage::disk('local');

        if ($disk->exists($file)) {
            return $disk->download($file);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy($fileName)
    {
        Log::info("Mencoba menghapus backup: " . $fileName);
        $backupFolderName = '';
        $file = $fileName;
        $disk = Storage::disk('local');

        if ($disk->exists($file)) {
            $disk->delete($file);
            Log::info("Backup berhasil dihapus: " . $fileName);
            return back()->with('success', 'Backup berhasil dihapus.');
        }

        Log::warning("Gagal menghapus backup. File tidak ditemukan: " . $fileName);
        return back()->with('error', 'File tidak ditemukan.');
    }

    public function uploadRestore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:204800', // max 200MB
        ], [
            'backup_file.required' => 'Pilih file backup ZIP terlebih dahulu.',
            'backup_file.mimes'    => 'File harus berformat ZIP.',
            'backup_file.max'      => 'Ukuran file maksimal 200MB.',
        ]);

        try {
            $uploadedFile = $request->file('backup_file');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            
            // Simpan sementara file yang diupload ke storage/app/
            $uploadedFile->move(storage_path('app'), $fileName);
            $zipPath = storage_path('app/' . $fileName);

            // Lanjutkan ke proses restore
            return $this->restoreFromPath($zipPath);

        } catch (\Exception $e) {
            Log::error('Upload Restore failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function restoreFromPath($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            $zip = new \ZipArchive();
            if ($zip->open($filePath) === TRUE) {
                $tempPath = storage_path('app/temp-restore-' . time());
                if (!is_dir($tempPath)) mkdir($tempPath, 0755, true);

                $zip->extractTo($tempPath);
                $zip->close();

                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempPath));
                $sqlFile = null;
                foreach ($files as $file) {
                    if ($file->isFile() && $file->getExtension() == 'sql') {
                        $sqlFile = $file->getRealPath();
                        break;
                    }
                }

                if ($sqlFile) {
                    $dbConfig = config('database.connections.' . config('database.default'));
                    
                    // Deteksi OS untuk path mysql
                    $mysqlPath = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'C:\\xampp\\mysql\\bin\\mysql' : 'mysql';

                    $pass = !empty($dbConfig['password']) ? '-p' . escapeshellarg($dbConfig['password']) : '';
                    $user = escapeshellarg($dbConfig['username']);
                    $db = escapeshellarg($dbConfig['database']);
                    $sqlFileSafe = escapeshellarg($sqlFile);
                    
                    $command = "\"{$mysqlPath}\" -u{$user} {$pass} {$db} < {$sqlFileSafe} 2>&1";

                    exec($command, $output, $exitCode);
                    
                    // Cleanup
                    \Illuminate\Support\Facades\File::deleteDirectory($tempPath);
                    unlink($filePath); // Hapus file zip aslinya

                    if ($exitCode !== 0) {
                        return back()->with('error', 'Gagal import SQL: ' . implode(' ', $output));
                    }

                    return back()->with('success', 'Database berhasil dipulihkan dari file yang diupload!');
                }

                \Illuminate\Support\Facades\File::deleteDirectory($tempPath);
                unlink($filePath);
                return back()->with('error', 'File SQL tidak ditemukan di dalam ZIP.');
            }

            return back()->with('error', 'Gagal membuka file ZIP.');
        } catch (\Exception $e) {
            Log::error('Restore from path failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
