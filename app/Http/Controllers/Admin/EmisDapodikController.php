<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

// Exports
use App\Exports\Emis\StudentExport;
use App\Exports\Emis\TeacherExport;
use App\Exports\Emis\RombelExport;

// Imports
use App\Imports\Emis\StudentImport;
use App\Imports\Emis\TeacherImport;

class EmisDapodikController extends Controller
{
    public function index()
    {
        return view('admin.emis.index');
    }

    public function exportStudent()
    {
        return Excel::download(new StudentExport, 'emis_student_template.xlsx');
    }

    public function exportTeacher()
    {
        return Excel::download(new TeacherExport, 'emis_teacher_template.xlsx');
    }

    public function exportRombel()
    {
        return Excel::download(new RombelExport, 'emis_rombel_template.xlsx');
    }

    public function importStudent(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new StudentImport, $request->file('file'));
            return back()->with('success', 'Data Siswa berhasil disinkronisasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    public function importTeacher(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new TeacherImport, $request->file('file'));
            return back()->with('success', 'Data Guru berhasil disinkronisasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
