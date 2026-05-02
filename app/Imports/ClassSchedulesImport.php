<?php

namespace App\Imports;

use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClassSchedulesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $subject = Subject::where('name', 'like', '%' . $row['mata_pelajaran'] . '%')->first();
        $teacher = Teacher::where('name', 'like', '%' . $row['nama_guru'] . '%')->first();
        
        // Class mapping: "7-A" or "7 A"
        $classParts = explode('-', str_replace(' ', '-', $row['kelas']));
        $class = ClassGroup::where('class_group', $classParts[0] ?? '')
            ->where('sub_class_group', $classParts[1] ?? '')
            ->first();

        $academicYear = AcademicYear::where('academic_year', $row['tahun_pelajaran'])->first() ?? AcademicYear::where('current_semester', true)->first();

        if ($subject && $teacher && $class) {
            // Day mapping: "Senin" -> 1
            $days = ['senin' => 1, 'selasa' => 2, 'rabu' => 3, 'kamis' => 4, 'jumat' => 5, 'sabtu' => 6, 'minggu' => 7];
            $day = $days[strtolower($row['hari'])] ?? 1;

            return new ClassSchedule([
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'class_group_id' => $class->id,
                'academic_year_id' => $academicYear->id,
                'day' => $day,
                'start_time' => $row['jam_mulai'],
                'end_time' => $row['jam_selesai'],
            ]);
        }

        return null;
    }
}
