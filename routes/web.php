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
    WelcomeMessageController
};

use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

// Route untuk postingan detail & komentar
Route::get('/post/{slug}', [FrontController::class, 'show'])->name('front.post_show');
Route::post('/post/{id}/comment', [FrontController::class, 'postComment'])->name('post.comment');
Route::get('/post/{id}/comments', [FrontController::class, 'showComments'])->name('post.showComments');

// Route dinamis berdasarkan slug menu
Route::get('/{slug}', [FrontController::class, 'handle'])->name('front.handle');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['role_or_permission:dashboard.view']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
            Route::delete('/user/profile', 'show')->name('profile.show');
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

    Route::group(['middleware' => ['permission:student-admission.view']], function () {
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
            // Route::put('/blog/opening-speech/{id}', 'update')->name('opening_speech.update');
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


    Route::controller(StudentController::class)->group(function () {
        Route::get('/academic/students/data', 'data')->name('students.data');
        Route::get('/academic/students', 'index')->name('students.index');
        Route::get('/academic/students/{id}', 'show')->name('students.show');
        Route::put('/academic/students/{id}', 'update')->name('students.update');
        Route::post('/academic/students/import-excel', 'importEXCEL')->name('students.import_excel');
        Route::post('/academic/students', 'store')->name('students.store');
        Route::post('/academic/students/delete-selected', 'deleteSelected')->name('students.deleteSelected');
        Route::delete('/academic/students/{id}/destroy', 'destroy')->name('students.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| DYNAMIC FRONTEND SLUG (WAJIB PALING BAWAH)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [FrontController::class, 'handle'])
    ->where('slug', '^(?!admin|users|user|role|permissions|permissiongroups|setting|academic|admission|blog|media|configuration|post$)[A-Za-z0-9\-]+')
    ->name('front.handle');
