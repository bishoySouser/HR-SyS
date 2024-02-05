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
        // (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('department', 'Company\Department');
    Route::crud('job', 'Company\Job');


    Route::crud('vacation-balance', 'Vacations\VacationBalanceCrudController');
    Route::crud('vacation', 'Vacations\VacationCrudController');
    Route::crud('employee', 'EmployeeCrudController');

    Route::crud('attendance', 'AttendanceCrudController');
    Route::crud('work-from-home', 'WorkFromHomeCrudController');
    Route::crud('excuse', 'ExcuseCrudController');
}); // this should be the absolute last line of this file
