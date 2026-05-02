<?php

use App\Http\Controllers\{
    AcademicYearController,
    AdmissionPhaseController,
    AdmissionQuotasController,
    AdmissionTypeController,
    AlbumController,
    CategoryController,
    ClassGroupController,
    DashboardController,
    EducationController,
    ImageSliderController,
    MenuController,
    MonthlyIncomeController,
    PageController,
    PermissionController,
    PermissionGroupController,
    PostController,
    QuotesController,
    ResidenceController,
    RoleController,
    SchoolAgendaController,
    SettingController,
    StudentAdmissionController,
    StudentController,
    StudentStatusController,
    TagController,
    TransportationController,
    UserController,
    WelcomeMessageController,
    PpdbRegistrantController,
    MailSettingController,
    OutgoingMailController,
    StudentCertificateController,
    StudentTransferController,
    SchoolMeetingController,
    StudentActiveStatementController,
    TeacherController,
    DutyLetterController,
    StudentPromotionController,
    StudentGraduationController,
    AttendanceSettingController,
    HolidayController,
    AttendanceReportController,
    TeacherAttendanceController,
    SubjectController,
    ClassScheduleController,
    StudentAttendanceController,
    StudyPeriodController,
    StudentPlacementController,
    StudentAcceptanceController,
    AnnouncementController
};

use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

// Route untuk postingan detail & komentar
Route::get('/post/{slug}', [FrontController::class, 'show'])->name('front.post_show');
Route::post('/post/{id}/comment', [FrontController::class, 'postComment'])->name('post.comment');

// PPDB Check
Route::get('/ppdb/check', [FrontController::class, 'showPpdbCheck'])->name('front.ppdb_check');
Route::post('/ppdb/check', [FrontController::class, 'checkPpdbStatus'])->name('front.ppdb_submit');
Route::get('/post/{id}/comments', [FrontController::class, 'showComments'])->name('post.showComments');

// QR Code Verification
Route::get('/ppdb/cek/{regNumber}', [\App\Http\Controllers\Ppdb\PpdbVerificationController::class, 'check'])->name('ppdb.check_verify');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['role_or_permission:dashboard.view|Super Admin|Admin|Guru|Siswa|ppdb']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/teacher/schedule', [DashboardController::class, 'teacherSchedule'])->name('teacher.schedule');
        Route::get('/class-students/{id}', [DashboardController::class, 'getClassStudents'])->name('teacher.class-students');

        // Announcements
        Route::get('/announcements', [AnnouncementController::class, 'teacherIndex'])->name('teacher.announcements');
        Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
    });

    // Admin Announcements
    Route::group(['prefix' => 'admin', 'middleware' => ['role:Super Admin|Admin']], function () {
        Route::get('/manage-announcements', [AnnouncementController::class, 'adminIndex'])->name('announcements.admin');
        Route::get('/announcements-data', [AnnouncementController::class, 'data'])->name('announcements.data');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    Route::group(['middleware' => ['permission:user.view']], function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/ajax/users/role_search', 'roleSearch')->name('users.role_search');
            Route::get('/users/data', 'data')->name('users.data');
            Route::get('/users', 'index')->name('users.index');
            Route::get('/users/{users}/detail', 'detail')->name('users.detail');
            Route::get('/users/{users}', 'edit')->name('users.edit');
            Route::put('/users/{users}/update', 'update')->name('users.update');
            Route::post('/users', 'store')->name('users.store');
            Route::delete('/users/{users}/destroy', 'destroy')->name('users.destroy');
            Route::post('/users/{users}/reset-password', 'resetPassword')->name('users.reset_password');
            Route::get('/user/profile', 'show')->name('profile.show');
        });
    });

    Route::group(['middleware' => ['permission:role.view']], function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/role/data', 'data')->name('role.data');
            Route::get('/role', 'index')->name('role.index');
            Route::get('/role/{role}/detail', 'detail')->name('role.detail');
            Route::get('/role/{role}', 'edit')->name('role.edit');
            Route::put('/role/{role}/update', 'update')->name('role.update');
            Route::post('/role', 'store')->name('role.store');
            Route::delete('/role/{role}/destroy', 'destroy')->name('role.destroy');
        });
    });

    Route::group(['middleware' => ['permission:permission.view']], function () {
        Route::controller(PermissionController::class)->group(function () {
            Route::get('/permissions/data', 'data')->name('permission.data');
            Route::get('/permissions', 'index')->name('permission.index');
            Route::get('/permissions/{permission}/detail', 'detail')->name('permission.detail');
            Route::get('/permissions/{permission}', 'edit')->name('permission.edit');
            Route::put('/permissions/{permission}/update', 'update')->name('permission.update');
            Route::post('/permissions', 'store')->name('permission.store');
            Route::delete('/permissions/{permission}/destroy', 'destroy')->name('permission.destroy');
        });
    });

    Route::group(['middleware' => ['permission:permission-group.view']], function () {
        Route::controller(PermissionGroupController::class)->group(function () {
            Route::get('/permissiongroups/data', 'data')->name('permissiongroups.data');
            Route::get('/permissiongroups', 'index')->name('permissiongroups.index');
            Route::get('/permissiongroups/{permissionGroup}/detail', 'detail')->name('permissiongroups.detail');
            Route::get('/permissiongroups/{permissionGroup}', 'edit')->name('permissiongroups.edit');
            Route::put('/permissiongroups/{permissionGroup}/update', 'update')->name('permissiongroups.update');
            Route::post('/permissiongroups', 'store')->name('permissiongroups.store');
            Route::delete('/permissiongroups/{permissionGroup}/destroy', 'destroy')->name('permissiongroups.destroy');
        });
    });

    Route::group(['middleware' => ['permission:setting.view']], function () {
        Route::controller(SettingController::class)->group(function () {
            Route::get('/setting', 'index')->name('setting.index');
            Route::put('/setting/{setting}', 'update')->name('setting.update');
        });
    });

    Route::group(['middleware' => ['permission:academic-year.view']], function () {
        Route::controller(AcademicYearController::class)->group(function () {
            Route::get('/academic/academic-years/data', 'data')->name('academic-years.data');
            Route::get('/academic/academic-years', 'index')->name('academic-years.index');
            Route::get('/academic/academic-years/{id}/detail', 'detail')->name('academic-years.detail');
            Route::get('/academic/academic-years/{id}', 'edit')->name('academic-years.edit');
            Route::put('/academic/academic-years/{id}/update', 'update')->name('academic-years.update');
            Route::put('/academic/academic-years/{id}/update/current-semester', 'updateCurrentSemester')->name('academic-years.update.current_semester');
            Route::put('/academic/academic-years/{id}/update/admission-semester', 'updateAdmissionSemester')->name('academic-years.update.admission_semester');
            Route::post('/academic/academic-years', 'store')->name('academic-years.store');
            Route::delete('/academic/academic-years/{id}/destroy', 'destroy')->name('academic-years.destroy');
        });
    });

    Route::group(['middleware' => ['permission:class-group.view']], function () {
        Route::controller(ClassGroupController::class)->group(function () {
            Route::get('/academic/class-groups/data', 'data')->name('class-groups.data');
            Route::get('/academic/class-groups', 'index')->name('class-groups.index');
            Route::get('/academic/class-groups/{id}', 'show')->name('class-groups.show');
            Route::put('/academic/class-groups/{id}', 'update')->name('class-groups.update');
            Route::post('/academic/class-groups', 'store')->name('class-groups.store');
            Route::delete('/academic/class-groups/{id}/destroy', 'destroy')->name('class-groups.destroy');
        });
    });

    Route::group(['middleware' => ['permission:transportation.view']], function () {
        Route::controller(TransportationController::class)->group(function () {
            Route::get('/academic/transportations/data', 'data')->name('transportations.data');
            Route::get('/academic/transportations', 'index')->name('transportations.index');
            Route::get('/academic/transportations/{id}', 'show')->name('transportations.show');
            Route::put('/academic/transportations/{id}', 'update')->name('transportations.update');
            Route::post('/academic/transportations/import-excel', 'importEXCEL')->name('transportations.import_excel');
            Route::post('/academic/transportations', 'store')->name('transportations.store');
            Route::delete('/academic/transportations/{id}/destroy', 'destroy')->name('transportations.destroy');
        });
    });

    Route::group(['middleware' => ['permission:monthly-income.view']], function () {
        Route::controller(MonthlyIncomeController::class)->group(function () {
            Route::get('/academic/monthly-incomes/data', 'data')->name('monthly-incomes.data');
            Route::get('/academic/monthly-incomes', 'index')->name('monthly-incomes.index');
            Route::get('/academic/monthly-incomes/{id}', 'show')->name('monthly-incomes.show');
            Route::put('/academic/monthly-incomes/{id}', 'update')->name('monthly-incomes.update');
            Route::post('/academic/monthly-incomes/import-excel', 'importEXCEL')->name('monthly-incomes.import_excel');
            Route::post('/academic/monthly-incomes', 'store')->name('monthly-incomes.store');
            Route::delete('/academic/monthly-incomes/{id}/destroy', 'destroy')->name('monthly-incomes.destroy');
        });
    });

    Route::group(['middleware' => ['permission:educations.view']], function () {
        Route::controller(EducationController::class)->group(function () {
            Route::get('/academic/educations/data', 'data')->name('educations.data');
            Route::get('/academic/educations', 'index')->name('educations.index');
            Route::get('/academic/educations/{id}', 'show')->name('educations.show');
            Route::put('/academic/educations/{id}', 'update')->name('educations.update');
            Route::post('/academic/educations/import-excel', 'importEXCEL')->name('educations.import_excel');
            Route::post('/academic/educations', 'store')->name('educations.store');
            Route::delete('/academic/educations/{id}/destroy', 'destroy')->name('educations.destroy');
        });
    });

    Route::group(['middleware' => ['permission:student-status.view']], function () {
        Route::controller(StudentStatusController::class)->group(function () {
            Route::get('/academic/student-status/data', 'data')->name('student-status.data');
            Route::get('/academic/student-status', 'index')->name('student-status.index');
            Route::get('/academic/student-status/{id}', 'show')->name('student-status.show');
            Route::put('/academic/student-status/{id}', 'update')->name('student-status.update');
            Route::post('/academic/student-status/import-excel', 'importEXCEL')->name('student-status.import_excel');
            Route::post('/academic/student-status', 'store')->name('student-status.store');
            Route::delete('/academic/student-status/{id}/destroy', 'destroy')->name('student-status.destroy');
        });
    });

    Route::group(['middleware' => ['permission:residences.view']], function () {
        Route::controller(ResidenceController::class)->group(function () {
            Route::get('/academic/residences/data', 'data')->name('residences.data');
            Route::get('/academic/residences', 'index')->name('residences.index');
            Route::get('/academic/residences/{id}', 'show')->name('residences.show');
            Route::put('/academic/residences/{id}', 'update')->name('residences.update');
            Route::post('/academic/residences/import-excel', 'importEXCEL')->name('residences.import_excel');
            Route::post('/academic/residences', 'store')->name('residences.store');
            Route::delete('/academic/residences/{id}/destroy', 'destroy')->name('residences.destroy');
        });
    });

    Route::group(['middleware' => ['permission:student-admissions.view']], function () {
        Route::controller(StudentAdmissionController::class)->group(function () {
            Route::get('/admission/student-admissions/data', 'data')->name('student-admissions.data');
            Route::get('/admission/student-admissions', 'index')->name('student-admissions.index');
            Route::get('/admission/student-admissions/{id}', 'show')->name('student-admissions.show');
            Route::put('/admission/student-admissions/{id}', 'update')->name('student-admissions.update');
            Route::post('/admission/student-admissions', 'store')->name('student-admissions.store');
        });
    });

    Route::group(['middleware' => ['permission:admission-phases.view']], function () {
        Route::controller(AdmissionPhaseController::class)->group(function () {
            Route::get('/admission/admission-phases/data', 'data')->name('admission-phases.data');
            Route::get('/admission/admission-phases', 'index')->name('admission-phases.index');
            Route::get('/admission/admission-phases/{id}', 'show')->name('admission-phases.show');
            Route::put('/admission/admission-phases/{id}', 'update')->name('admission-phases.update');
            Route::post('/admission/admission-phases/import-excel', 'importEXCEL')->name('admission-phases.import_excel');
            Route::post('/admission/admission-phases', 'store')->name('admission-phases.store');
            Route::delete('/admission/admission-phases/{id}/destroy', 'destroy')->name('admission-phases.destroy');
        });
    });

    Route::group(['middleware' => ['permission:admission-types.view']], function () {
        Route::controller(AdmissionTypeController::class)->group(function () {
            Route::get('/admission/admission-types/data', 'data')->name('admission-types.data');
            Route::get('/admission/admission-types', 'index')->name('admission-types.index');
            Route::get('/admission/admission-types/{id}', 'show')->name('admission-types.show');
            Route::put('/admission/admission-types/{id}', 'update')->name('admission-types.update');
            Route::post('/admission/admission-types/import-excel', 'importEXCEL')->name('admission-types.import_excel');
            Route::post('/admission/admission-types', 'store')->name('admission-types.store');
            Route::delete('/admission/admission-types/{id}/destroy', 'destroy')->name('admission-types.destroy');
        });
    });

    Route::group(['middleware' => ['permission:admission-quotas.view']], function () {
        Route::controller(AdmissionQuotasController::class)->group(function () {
            Route::get('/admission/admission-quotas/data', 'data')->name('admission-quotas.data');
            Route::get('/admission/admission-quotas', 'index')->name('admission-quotas.index');
            Route::get('/admission/admission-quotas/{id}', 'show')->name('admission-quotas.show');
            Route::put('/admission/admission-quotas/{id}', 'update')->name('admission-quotas.update');
            Route::post('/admission/admission-quotas/import-excel', 'importEXCEL')->name('admission-quotas.import_excel');
            Route::post('/admission/admission-quotas', 'store')->name('admission-quotas.store');
            Route::delete('/admission/admission-quotas/{id}/destroy', 'destroy')->name('admission-quotas.destroy');
        });
    });

    Route::group(['middleware' => ['permission:image-sliders.view']], function () {
        Route::controller(ImageSliderController::class)->group(function () {
            Route::get('/blog/image-sliders/data', 'data')->name('image-sliders.data');
            Route::get('/blog/image-sliders', 'index')->name('image-sliders.index');
            Route::get('/blog/image-sliders/{id}', 'show')->name('image-sliders.show');
            Route::put('/blog/image-sliders/{id}', 'update')->name('image-sliders.update');
            Route::post('/blog/image-sliders/import-excel', 'importEXCEL')->name('image-sliders.import_excel');
            Route::post('/blog/image-sliders', 'store')->name('image-sliders.store');
            Route::post('/blog/image-sliders/delete-selected', 'deleteSelected')->name('image-sliders.deleteSelected');
            Route::delete('/blog/image-sliders/{id}/destroy', 'destroy')->name('image-sliders.destroy');
        });
    });

    Route::group(['middleware' => ['permission:categories.view']], function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/blog/categories/data', 'data')->name('categories.data');
            Route::get('/blog/categories', 'index')->name('categories.index');
            Route::get('/blog/categories/{id}', 'show')->name('categories.show');
            Route::put('/blog/categories/{id}', 'update')->name('categories.update');
            Route::post('/blog/categories/import-excel', 'importEXCEL')->name('categories.import_excel');
            Route::post('/blog/categories', 'store')->name('categories.store');
            Route::post('/blog/categories/delete-selected', 'deleteSelected')->name('categories.deleteSelected');
            Route::delete('/blog/categories/{id}/destroy', 'destroy')->name('categories.destroy');
        });
    });

    Route::group(['middleware' => ['permission:tags.view']], function () {
        Route::controller(TagController::class)->group(function () {
            Route::get('/blog/tags/data', 'data')->name('tags.data');
            Route::get('/blog/tags', 'index')->name('tags.index');
            Route::get('/blog/tags/{id}', 'show')->name('tags.show');
            Route::put('/blog/tags/{id}', 'update')->name('tags.update');
            Route::post('/blog/tags/import-excel', 'importEXCEL')->name('tags.import_excel');
            Route::post('/blog/tags', 'store')->name('tags.store');
            Route::post('/blog/tags/delete-selected', 'deleteSelected')->name('tags.deleteSelected');
            Route::delete('/blog/tags/{id}/destroy', 'destroy')->name('tags.destroy');
        });
    });

    Route::group(['middleware' => ['permission:posts.view']], function () {
        Route::controller(PostController::class)->group(function () {
            Route::get('/blog/posts/data', 'data')->name('posts.data');
            Route::get('/blog/posts', 'index')->name('posts.index');
            Route::get('/blog/posts/create', 'create')->name('posts.create');
            Route::get('/blog/posts/{id}', 'show')->name('posts.show');
            Route::get('/blog/posts/{id}/edit', 'edit')->name('posts.edit');
            Route::put('/blog/posts/{id}', 'update')->name('posts.update');
            Route::post('/blog/posts/import-excel', 'importEXCEL')->name('posts.import_excel');
            Route::post('/blog/posts', 'store')->name('posts.store');
            Route::post('/blog/posts/delete-selected', 'deleteSelected')->name('posts.deleteSelected');
            Route::delete('/blog/posts/{id}/destroy', 'destroy')->name('posts.destroy');
        });
    });

    Route::group(['middleware' => ['permission:albums.view']], function () {
        Route::controller(AlbumController::class)->group(function () {
            Route::get('/media/albums/data', 'data')->name('albums.data');
            Route::get('/media/albums', 'index')->name('albums.index');
            Route::get('/media/albums/{id}', 'show')->name('albums.show');
            Route::put('/media/albums/{id}', 'update')->name('albums.update');
            Route::post('/media/albums', 'store')->name('albums.store');
            Route::post('/media/albums/delete-selected', 'deleteSelected')->name('albums.deleteSelected');
            Route::delete('/media/albums/{id}/destroy', 'destroy')->name('albums.destroy');
        });
    });

    Route::group(['middleware' => ['permission:quotes.view']], function () {
        Route::controller(QuotesController::class)->group(function () {
            Route::get('/blog/quotes/data', 'data')->name('quotes.data');
            Route::get('/blog/quotes', 'index')->name('quotes.index');
            Route::get('/blog/quotes/{id}', 'show')->name('quotes.show');
            Route::put('/blog/quotes/{id}', 'update')->name('quotes.update');
            Route::post('/blog/quotes/import-excel', 'importEXCEL')->name('quotes.import_excel');
            Route::post('/blog/quotes', 'store')->name('quotes.store');
            Route::post('/blog/quotes/delete-selected', 'deleteSelected')->name('quotes.deleteSelected');
            Route::delete('/blog/quotes/{id}/destroy', 'destroy')->name('quotes.destroy');
        });
    });

    Route::group(['middleware' => ['permission:opening-speech.view']], function () {
        Route::controller(WelcomeMessageController::class)->group(function () {
            Route::get('/blog/opening-speech', 'index')->name('opening_speech.index');
            Route::get('opening-speech/edit', 'edit')->name('opening_speech.edit');
            Route::post('opening-speech/store', 'store')->name('opening_speech.store');
            Route::put('opening-speech/update/{id}', 'update')->name('opening_speech.update');
        });
    });

    // Menu
    Route::controller(MenuController::class)->group(function () {
        Route::get('/configuration/menus/data', 'getAllMenu')->name('menus.getAllMenu');
        Route::get('/configuration/menus/sub-menus', 'getAllSubmenu')->name('menus.getAllSubmenu');
        Route::get('/configuration/menus', 'index')->name('menus.index');
        Route::get('/configuration/menus/{id}', 'show')->name('menus.show');
        Route::put('/configuration/menus/{id}', 'update')->name('menus.update');
        Route::post('/configuration/menus/update-order', 'updateOrder')->name('menus.updateOrder');
        Route::post('/configuration/menus', 'store')->name('menus.store');
        Route::post('/configuration/menus/reset', 'reset')->name('menus.reset');
        Route::delete('/configuration/menus/{id}/destroy', 'destroy')->name('menus.destroy');
    });

    // Page
    Route::controller(PageController::class)->group(function () {
        Route::get('/blog/pages/data', 'data')->name('pages.data');
        Route::get('/blog/pages', 'index')->name('pages.index');
        Route::get('/blog/pages/create', 'create')->name('pages.create');
        Route::post('/blog/pages', 'store')->name('pages.store');
        Route::get('/blog/pages/{id}', 'show')->name('pages.show');
        Route::put('/blog/pages/{id}', 'update')->name('pages.update');
        Route::delete('/blog/pages/{id}/destroy', 'destroy')->name('pages.destroy');
    });

    Route::group(['middleware' => ['permission:categories.view']], function () {
        Route::controller(SchoolAgendaController::class)->group(function () {
            Route::get('/academic/agenda/data', 'data')->name('agenda.data');
            Route::get('/academic/agenda', 'index')->name('agenda.index');
            Route::get('/academic/agenda/{id}', 'show')->name('agenda.show');
            Route::put('/academic/agenda/{id}', 'update')->name('agenda.update');
            Route::post('/academic/agenda/import-excel', 'importEXCEL')->name('agenda.import_excel');
            Route::post('/academic/agenda', 'store')->name('agenda.store');
            Route::post('/academic/agenda/delete-selected', 'deleteSelected')->name('agenda.deleteSelected');
            Route::delete('/academic/agenda/{id}/destroy', 'destroy')->name('agenda.destroy');
        });
    });

    Route::controller(PpdbRegistrantController::class)->group(function () {
        Route::get('/admission/ppdb/data', 'data')->name('ppdb.data');
        Route::get('/admission/ppdb', 'index')->name('ppdb.index');
        Route::get('/admission/ppdb/print-berita-acara', 'printBeritaAcara')->name('ppdb.print_berita_acara');
        Route::get('/admission/ppdb/print-collective-sk', 'printCollectiveSK')->name('ppdb.print_collective_sk');
        Route::get('/admission/ppdb/{id}', 'show')->name('ppdb.show');
        Route::put('/admission/ppdb/{id}', 'update')->name('ppdb.update');
        Route::post('/admission/ppdb', 'store')->name('ppdb.store');
        Route::post('/admission/ppdb/{id}/verify', 'verify')->name('ppdb.verify');
        Route::get('/admission/ppdb/document/{id}/download', 'downloadBerkas')->name('ppdb.download_berkas');
        Route::get('/admission/ppdb/{id}/print-letter', 'printLetter')->name('ppdb.print_letter');
        Route::delete('/admission/ppdb/{id}/destroy', 'destroy')->name('ppdb.destroy');
    });

    Route::controller(StudentController::class)->group(function () {
        Route::get('/academic/students/data', 'data')->name('students.data');
        Route::get('/academic/students/download-template', 'downloadTemplate')->name('students.download_template');
        Route::get('/academic/students', 'index')->name('students.index');
        Route::get('/academic/students/export-excel', 'exportExcel')->name('students.export_excel');
        Route::get('/academic/students/export-pdf', 'exportPDF')->name('students.export_pdf');
        Route::post('/academic/students', 'store')->name('students.store');
        Route::post('/academic/students/import-excel', 'importEXCEL')->name('students.import_excel');
        Route::get('/academic/students/{id}', 'show')->name('students.show');
        Route::put('/academic/students/{id}', 'update')->name('students.update');
        Route::delete('/academic/students/{id}/destroy', 'destroy')->name('students.destroy');
        Route::post('/academic/students/delete-selected', 'deleteSelected')->name('students.deleteSelected');
    });

    // PPDB Student Area
    Route::group(['middleware' => ['role:ppdb'], 'prefix' => 'ppdb'], function () {
        Route::get('/dashboard', [\App\Http\Controllers\Ppdb\PpdbDashboardController::class, 'index'])->name('ppdb.dashboard');
        Route::get('/print-registration', [\App\Http\Controllers\Ppdb\PpdbDashboardController::class, 'printRegistration'])->name('ppdb.print_registration');
        Route::get('/print-verification', [\App\Http\Controllers\Ppdb\PpdbDashboardController::class, 'printVerification'])->name('ppdb.print_verification');
        Route::post('/biodata', [\App\Http\Controllers\Ppdb\PpdbDashboardController::class, 'storeBiodata'])->name('ppdb.store_biodata');
        Route::put('/biodata', [\App\Http\Controllers\Ppdb\PpdbDashboardController::class, 'updateBiodata'])->name('ppdb.update_biodata');
    });

    // Root redirect based on role
    Route::get('/home', function () {
        if (auth()->user()->hasRole('ppdb')) {
            return redirect()->route('ppdb.dashboard');
        }
        return redirect()->route('dashboard');
    })->name('home');

    // Persuratan
    Route::prefix('mail')->group(function () {
        // Mail Settings (Kop Surat)
        Route::get('/settings', [MailSettingController::class, 'index'])->name('mail-settings.index');
        Route::post('/settings', [MailSettingController::class, 'update'])->name('mail-settings.update');

        // Outgoing Mail
        Route::controller(OutgoingMailController::class)->group(function () {
            Route::get('/outgoing/data', 'data')->name('outgoing-mails.data');
            Route::get('/outgoing', 'index')->name('outgoing-mails.index');
            Route::get('/outgoing/{id}/show', 'show')->name('outgoing-mails.show');
            Route::get('/outgoing/{id}/print', 'print')->name('outgoing-mails.print');
            Route::post('/outgoing', 'store')->name('outgoing-mails.store');
            Route::put('/outgoing/{id}', 'update')->name('outgoing-mails.update');
            Route::delete('/outgoing/{id}/destroy', 'destroy')->name('outgoing-mails.destroy');
        });

        // Student Certificates
        Route::controller(StudentCertificateController::class)->group(function () {
            Route::get('/certificates/data', 'data')->name('student-certificates.data');
            Route::get('/certificates', 'index')->name('student-certificates.index');
            Route::get('/certificates/{id}/show', 'show')->name('student-certificates.show');
            Route::get('/certificates/{id}/print', 'print')->name('student-certificates.print');
            Route::post('/certificates', 'store')->name('student-certificates.store');
            Route::put('/certificates/{id}', 'update')->name('student-certificates.update');
            Route::delete('/certificates/{id}/destroy', 'destroy')->name('student-certificates.destroy');
        });

        // Student Active Statements
        Route::controller(StudentActiveStatementController::class)->group(function () {
            Route::get('/active-statements/data', 'data')->name('active-statements.data');
            Route::get('/active-statements', 'index')->name('active-statements.index');
            Route::get('/active-statements/{id}/show', 'show')->name('active-statements.show');
            Route::get('/active-statements/{id}/print', 'print')->name('active-statements.print');
            Route::post('/active-statements', 'store')->name('active-statements.store');
            Route::put('/active-statements/{id}', 'update')->name('active-statements.update');
            Route::delete('/active-statements/{id}/destroy', 'destroy')->name('active-statements.destroy');
        });

        // Student Transfers (Mutasi)
        Route::controller(StudentTransferController::class)->group(function () {
            Route::get('/transfers/data', 'data')->name('student-transfers.data');
            Route::get('/transfers', 'index')->name('student-transfers.index');
            Route::get('/transfers/{id}/show', 'show')->name('student-transfers.show');
            Route::get('/transfers/{id}/print', 'print')->name('student-transfers.print');
            Route::post('/transfers', 'store')->name('student-transfers.store');
            Route::put('/transfers/{id}', 'update')->name('student-transfers.update');
            Route::delete('/transfers/{id}/destroy', 'destroy')->name('student-transfers.destroy');
        });

        // School Meetings (Undangan Rapat)
        Route::controller(SchoolMeetingController::class)->group(function () {
            Route::get('/meetings/data', 'data')->name('school-meetings.data');
            Route::get('/meetings', 'index')->name('school-meetings.index');
            Route::get('/meetings/{id}/show', 'show')->name('school-meetings.show');
            Route::get('/meetings/{id}/print', 'print')->name('school-meetings.print');
            Route::post('/meetings', 'store')->name('school-meetings.store');
            Route::put('/meetings/{id}', 'update')->name('school-meetings.update');
            Route::delete('/meetings/{id}/destroy', 'destroy')->name('school-meetings.destroy');
        });
    });

    // Teacher Management
    Route::get('/teachers/data', [TeacherController::class, 'data'])->name('teachers.data');
    Route::get('/teachers/download-template', [TeacherController::class, 'downloadTemplate'])->name('teachers.download_template');
    Route::post('/teachers/import-excel', [TeacherController::class, 'importExcel'])->name('teachers.import_excel');
    Route::resource('/teachers', TeacherController::class);

    // Duty Letters (Surat Tugas & SPPD)
    Route::get('/duty-letters/data', [DutyLetterController::class, 'data'])->name('duty-letters.data');
    Route::get('/duty-letters/{id}/print-st', [DutyLetterController::class, 'printST'])->name('duty-letters.print-st');
    Route::get('/duty-letters/{id}/print-sppd', [DutyLetterController::class, 'printSPPD'])->name('duty-letters.print-sppd');
    Route::resource('/duty-letters', DutyLetterController::class);

    // Student Promotions (Kenaikan Kelas / Rombel)
    Route::get('/promotions/data', [StudentPromotionController::class, 'data'])->name('promotions.data');
    Route::post('/promotions/promote', [StudentPromotionController::class, 'promote'])->name('promotions.promote');
    Route::post('/promotions/undo', [StudentPromotionController::class, 'undo'])->name('promotions.undo');
    Route::resource('/promotions', StudentPromotionController::class);

    // Student Placements (Penempatan Rombel)
    Route::get('academic/student-placements/data', [StudentPlacementController::class, 'data'])->name('student-placements.data');
    Route::resource('academic/student-placements', StudentPlacementController::class);

    // Student Graduations (Kelulusan)
    Route::get('/graduations/data', [StudentGraduationController::class, 'data'])->name('graduations.data');
    Route::post('/graduations/graduate', [StudentGraduationController::class, 'graduate'])->name('graduations.graduate');
    Route::post('/graduations/undo', [StudentGraduationController::class, 'undo'])->name('graduations.undo');
    Route::get('/graduations/{id}/print-skl', [StudentGraduationController::class, 'printSKL'])->name('graduations.print-skl');
    Route::resource('/graduations', StudentGraduationController::class);

    // Letter Number Generator
    Route::get('/mail/generate-letter-number', [\App\Http\Controllers\LetterNumberController::class, 'generate'])->name('letter-number.generate');

    // Student Acceptances (Bersedia Menerima)
    Route::get('admin/mail/student-acceptances/data', [StudentAcceptanceController::class, 'data'])->name('student-acceptances.data');
    Route::get('admin/mail/student-acceptances/{id}/print', [StudentAcceptanceController::class, 'print'])->name('student-acceptances.print');
    Route::resource('admin/mail/student-acceptances', StudentAcceptanceController::class)->names('student-acceptances');

    // Teacher Attendance Portal
    Route::get('/teacher/attendance', [TeacherAttendanceController::class, 'dashboard'])->name('teacher.attendance.dashboard');
    Route::post('/teacher/attendance/check-in', [TeacherAttendanceController::class, 'checkIn'])->name('teacher.attendance.check-in');
    Route::post('/teacher/attendance/check-out', [TeacherAttendanceController::class, 'checkOut'])->name('teacher.attendance.check-out');

    // Admin Attendance Management
    Route::prefix('attendance')->group(function () {
        Route::get('/settings', [AttendanceSettingController::class, 'index'])->name('attendance-settings.index');
        Route::post('/settings', [AttendanceSettingController::class, 'update'])->name('attendance-settings.update');

        Route::get('/holidays/data', [HolidayController::class, 'data'])->name('holidays.data');
        Route::resource('/holidays', HolidayController::class);

        Route::get('/reports/data', [AttendanceReportController::class, 'data'])->name('attendance-reports.data');
        Route::get('/reports/print', [AttendanceReportController::class, 'print'])->name('attendance-reports.print');
        Route::resource('/reports', AttendanceReportController::class)->names('attendance-reports');
    });

    Route::get('/study-periods/data', [StudyPeriodController::class, 'data'])->name('study-periods.data');
    Route::resource('/study-periods', StudyPeriodController::class);

    // Subjects & Schedules
    Route::get('/subjects/download-template', [SubjectController::class, 'downloadTemplate'])->name('subjects.download_template');
    Route::post('/subjects/import-excel', [SubjectController::class, 'importExcel'])->name('subjects.import_excel');
    Route::get('/subjects/data', [SubjectController::class, 'data'])->name('subjects.data');
    Route::resource('/subjects', SubjectController::class);

    Route::get('/class-schedules/download-template', [ClassScheduleController::class, 'downloadTemplate'])->name('class-schedules.download_template');
    Route::post('/class-schedules/import-excel', [ClassScheduleController::class, 'importExcel'])->name('class-schedules.import_excel');
    Route::get('/class-schedules/data', [ClassScheduleController::class, 'data'])->name('class-schedules.data');
    Route::resource('/class-schedules', ClassScheduleController::class);

    // Student Attendance (QR Scan)
    Route::prefix('student-attendances')->group(function () {
        Route::get('/data', [StudentAttendanceController::class, 'data'])->name('student-attendances.data');
        Route::get('/scanner', [StudentAttendanceController::class, 'scanner'])->name('student-attendances.scanner');
        Route::post('/scan', [StudentAttendanceController::class, 'scan'])->name('student-attendances.scan');
        Route::get('/cards', [StudentAttendanceController::class, 'printCards'])->name('student-attendances.cards');
        Route::get('/pdf', [StudentAttendanceController::class, 'pdf'])->name('student-attendances.pdf');
    });
    Route::resource('/student-attendances', StudentAttendanceController::class);
});

/*
|--------------------------------------------------------------------------
| DYNAMIC FRONTEND SLUG (WAJIB PALING BAWAH)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [FrontController::class, 'handle'])
    ->where('slug', '^(?!(admin|users|user|role|permissions|permissiongroups|setting|academic|admission|blog|media|configuration|post|home|dashboard)$)[A-Za-z0-9\-]+')
    ->name('front.handle');


Route::get('/fix-path', function () {
    // Menghapus cache bootstrap/cache/config.php dan services.php
    \Artisan::call('optimize:clear');

    // Mencoba menjalankan dump-autoload jika fungsi shell diizinkan hosting
    if (function_exists('shell_exec')) {
        shell_exec('composer dump-autoload');
        return "Cache dibersihkan dan Autoload diperbarui.";
    }

    return "Cache dibersihkan. Jika masih error, Anda harus hapus folder 'vendor' dan upload ulang folder 'vendor' yang baru di-generate dari lokal.";
});
