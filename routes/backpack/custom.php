<?php

use App\Http\Controllers\Api\V1\EmployeesEvaluationController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    // if not otherwise configured, setup the dashboard routes
    if (config('backpack.base.setup_dashboard_routes')) {
        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');
        Route::get('/', 'AdminController@redirect')->name('backpack');
    }

    Route::crud('department', 'Company\Department');
    Route::crud('job', 'Company\Job');


    Route::crud('vacation-balance', 'Vacations\VacationBalanceCrudController');
    Route::crud('vacation', 'Vacations\VacationCrudController');
    Route::crud('employee', 'EmployeeCrudController');
    Route::post('employee/resetPassword/empployee', 'EmployeeCrudController@resetPassword')->name('resetPassword');
    Route::post('/employee/bulk-delete', 'EmployeeCrudController@bulkDelete')->name('employee.bulk-delete');

    Route::crud('trashed-employee', 'TrashedEmployeeCrudController');
    Route::get('trashed-employee/{id}/restore', 'TrashedEmployeeCrudController@restore')->name('trashed-employee.restore');

    Route::get('employee/{id}/upload-attach', function () {
        return 'uploading attach for employees';
    });

    Route::crud('attendance', 'AttendanceCrudController');
    Route::crud('work-from-home', 'WorkFromHomeCrudController');
    Route::crud('excuse', 'ExcuseCrudController');

    Route::crud('social-insurance', 'Insurance\SocialInsuranceCrudController');
    Route::crud('medical-insurance', 'Insurance\MedicalInsuranceCrudController');
    Route::crud('event', 'EventCrudController');

    Route::crud('course', 'CourseCrudController');
    Route::crud('enrollment', 'EnrollmentCrudController');
    Route::crud('it-ticket', 'ItTicketCrudController');
    Route::crud('policy-document', 'PolicyDocumentCrudController');
    Route::crud('holiday', 'HolidayCrudController');

    Route::crud('employee-of-the-month', 'EmployeeOfTheMonthCrudController');

    Route::crud('best-employee-in-team', 'BestEmployeeInTeamCrudController');
    Route::crud('best-manager-in-company', 'BestManagerInCompanyCrudController');

    // Report
    Route::get('report/eom', 'Reports\EmployeeOfTheMonth@report')->name('employee.of.month.report');
    Route::crud('employee-evaluation', 'EmployeeEvaluationCrudController');

    Route::prefix('admin')->group(function () {
        Route::get('evaluations/pdf/{id}', [EmployeesEvaluationController::class, 'generatePdf'])
            ->name('evaluations.download');
    });
}); // this should be the absolute last line of this file
