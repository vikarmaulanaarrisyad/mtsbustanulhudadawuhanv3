<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Setting;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentCardController extends Controller
{
    /**
     * Tampilkan kartu siswa tunggal (Browser Print)
     */
    public function print($id)
    {
        $student = Student::with(['classGroup', 'academicYear'])->findOrFail($id);
        $setting = Setting::first();
        
        // Generate QR Code via Google Charts for better compatibility
        $qrData = $student->nisn ?? $student->id;
        $qrUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($qrData) . "&choe=UTF-8";
        
        $qrCodeBase64 = null;
        try {
            $qrContent = file_get_contents($qrUrl);
            if ($qrContent) {
                $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrContent);
            }
        } catch (\Exception $e) {
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(QrCode::size(150)->generate($qrData));
        }

        // Student Photo
        $studentPhotoBase64 = null;
        if ($student->profile && $student->profile->foto) {
            $path = public_path('storage/' . $student->profile->foto);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $studentPhotoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        return view('admin.academic.students.card', compact('student', 'setting', 'qrCodeBase64', 'studentPhotoBase64'));
    }

    public function downloadPdf($id)
    {
        $student = Student::with(['classGroup', 'academicYear', 'profile'])->findOrFail($id);
        $setting = Setting::first();
        $mailSetting = \App\Models\MailSetting::first();
        
        // Generate QR Code via Google Charts and encode to Base64 for 100% compatibility
        $qrData = $student->nisn ?? $student->id;
        $qrUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($qrData) . "&choe=UTF-8";
        
        $qrCodeBase64 = null;
        try {
            // Using Http client or file_get_contents
            $qrContent = file_get_contents($qrUrl);
            if ($qrContent) {
                $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrContent);
            }
        } catch (\Exception $e) {
            // Fallback to local SVG if remote fails
            $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode(QrCode::size(150)->generate($qrData));
        }
        
        // Convert Student Photo to Base64 to ensure it displays in PDF
        $studentPhotoBase64 = null;
        if ($student->profile && $student->profile->foto) {
            $path = public_path('storage/' . $student->profile->foto);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $studentPhotoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $isPdf = true;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.academic.students.card', compact(
            'student', 
            'setting', 
            'mailSetting', 
            'qrCodeBase64', 
            'studentPhotoBase64', 
            'isPdf'
        ));
        
        $pdf->setPaper('a4', 'portrait'); 

        return $pdf->stream('Kartu_NISN_' . str_replace(' ', '_', $student->nama_lengkap) . '.pdf');
    }

    /**
     * Cetak kolektif per kelas
     */
    public function printByClass($classGroupId)
    {
        $students = Student::with(['classGroup', 'academicYear'])
            ->where('student_class_group_id', $classGroupId)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();
            
        $setting = Setting::first();
        
        $cards = [];
        foreach ($students as $student) {
            $cards[] = [
                'student' => $student,
                'qrCode' => base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($student->nisn ?? $student->id))
            ];
        }

        return view('admin.academic.students.card_collective', compact('cards', 'setting'));
    }
}
