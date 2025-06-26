<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionType;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $academicYear = AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();

        $studentAdmission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();
        $statusPendaftaran = ($studentAdmission && $studentAdmission->admission_status == 'open') ? 'Dibuka' : 'Ditutup';

        // Ambil semua jenis pendaftaran + kuotanya
        $admissionTypes = AdmissionType::with('quota')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        // dd($admissionTypes);
        return view('admin.dashboard.index', compact('academicYear', 'statusPendaftaran', 'studentAdmission', 'admissionTypes'));
    }
}
