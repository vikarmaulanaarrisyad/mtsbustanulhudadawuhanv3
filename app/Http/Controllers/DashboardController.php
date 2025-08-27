<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionType;
use App\Models\Category;
use App\Models\Post;
use App\Models\StudentAdmission;
use App\Models\Tag;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $academicYear = AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();

        if (!$academicYear) {
            return view('admin.dashboard.index', [
                'academicYear' => null,
                'statusPendaftaran' => 'Tidak Ada Tahun Akademik Aktif',
                'studentAdmission' => null,
                'admissionTypes' => [],
                'postsCount' => Post::count(),
                'categoriesCount' => Category::count(),
                'tagsCount' => Tag::count(),
            ]);
        }

        $studentAdmission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();
        $statusPendaftaran = ($studentAdmission && $studentAdmission->admission_status == 'open') ? 'Dibuka' : 'Ditutup';

        $admissionTypes = AdmissionType::with('quota')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        $postsCount = Post::count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();

        return view('admin.dashboard.index', compact(
            'academicYear',
            'statusPendaftaran',
            'studentAdmission',
            'admissionTypes',
            'postsCount',
            'categoriesCount',
            'tagsCount'
        ));
    }
}
