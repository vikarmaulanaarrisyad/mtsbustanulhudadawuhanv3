<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\SchoolAgenda;
use App\Models\Tag;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\StudentAttendance;
use App\Models\ClassGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
 
class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $academicYear = AcademicYear::where('current_semester', 1)->first();
 
        // Basic Stats
        $studentsCount = Student::where('is_active', true)->count();
        $teachersCount = Teacher::count();
        $subjectsCount = Subject::count();
        $classesCount = ClassGroup::count();
 
        // Attendance Stats Today
        $teacherAttendanceCount = Attendance::where('date', $today)->count();
        $studentAttendanceCount = StudentAttendance::where('date', $today)->count();
        
        // Recent Student Attendance
        $recentAttendances = StudentAttendance::with(['student', 'classGroup'])
            ->where('date', $today)
            ->latest('time')
            ->take(5)
            ->get();
 
        // Original Stats
        $postsCount = Post::count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();
        $pageCount = Page::count();
        $agendaCount = SchoolAgenda::count();
        $attendanceTrend = $this->getAttendanceTrend();
 
        return view('admin.dashboard.index', compact(
            'academicYear',
            'studentsCount',
            'teachersCount',
            'subjectsCount',
            'classesCount',
            'teacherAttendanceCount',
            'studentAttendanceCount',
            'recentAttendances',
            'postsCount',
            'categoriesCount',
            'tagsCount',
            'pageCount',
            'agendaCount',
            'attendanceTrend'
        ));
    }
 
    private function getAttendanceTrend()
    {
        $days = [];
        $studentCounts = [];
        $teacherCounts = [];
 
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $days[] = Carbon::parse($date)->format('d M');
            
            $studentCounts[] = StudentAttendance::where('date', $date)->count();
            $teacherCounts[] = Attendance::where('date', $date)->count();
        }
 
        return [
            'labels' => $days,
            'students' => $studentCounts,
            'teachers' => $teacherCounts,
        ];
    }
}
