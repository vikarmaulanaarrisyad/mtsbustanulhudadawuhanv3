<?php
$u = \App\Models\User::where('email', 'guru1@example.com')->first();
Auth::login($u);

try {
    $view = view('guru.dashboard.index', [
        'teacher' => \App\Models\Teacher::where('user_id', $u->id)->first(),
        'schedules' => collect([]),
        'myAttendances' => collect([]),
        'todayAttendance' => null,
        'totalSchedules' => 0,
        'homeroomClass' => null,
        'myStudents' => collect([]), // Explicitly passing collection
        'unreadAnnouncementsCount' => 0,
        'myPermits' => collect([]),
        'onLeave' => false,
        'announcements' => collect([]),
        'setting' => \App\Models\AttendanceSetting::first(),
        'layout' => 'layouts.app' // Assuming layout exists
    ]);
    echo "View made successfully" . PHP_EOL;
    $view->render();
    echo "View rendered successfully" . PHP_EOL;
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
