<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Setting;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentCardController extends Controller
{
    /**
     * Tampilkan kartu siswa tunggal
     */
    public function print($id)
    {
        $student = Student::with(['classGroup', 'academicYear'])->findOrFail($id);
        $setting = Setting::first();
        
        // Generate QR Code (Isinya NISN atau ID Siswa)
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($student->nisn ?? $student->id));

        return view('admin.academic.students.card', compact('student', 'setting', 'qrCode'));
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
