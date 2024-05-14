<?php

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
}); // this should be the absolute last line of this file
