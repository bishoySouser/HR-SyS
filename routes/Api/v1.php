<?php

use App\Http\Controllers\Api\V1\Auth\User;
use App\Http\Controllers\Api\V1\EmployeeController;
use Illuminate\Support\Facades\Route;

// Route::post('register',[User::class,'register']);
Route::post('login',[User::class,'login']);
Route::post('logout',[User::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    //change password
    Route::post('/change-password', [User::class,'changePassword']);

    // dashboard
    Route::get('/dashboard', 'DashboardController@index');

    // employees
    Route::group(['prefix' => 'employees'], function() {
        Route::apiResource('', EmployeeController::class);
        Route::get('profile', 'EmployeeController@getProfile');
        Route::get('tree', 'EmployeeController@getEmployeeTree');
        Route::get('managers-wihtout-your-employee', 'EmployeeController@getManagersWithoutTheirOwnManager')->middleware('is_not_manager');

        // employee-rate
        Route::prefix('rate')->group(function() {
            Route::post('managers', 'EmployeeRateController@managerRate')->middleware('is_not_manager');
            Route::post('team', 'EmployeeRateController@voteEmployeeOfTeam')->middleware('manager');
        });
    });

    // employee of the months
    Route::group(['prefix' => 'employee-of-the-month'], function() {
        Route::get('', 'EmployeeOfTheMonthController@index');
    });

    // vacations
    Route::prefix('vacations')->group(function () {
        Route::get('', 'VacationController@index');
        Route::post('', 'VacationController@store');
    });

    // work from home
    Route::prefix('work-from-home')->group(function () {
        Route::get('', 'WorkFromHomeController@index');
        Route::post('', 'WorkFromHomeController@store');
    });

    // excueses
    Route::prefix('excuses')->group(function() {
        Route::post('', "ExcuseController@store");
        Route::get('', "ExcuseController@index");
    });

    // Request all for team (manager)
    Route::prefix('team')->middleware('manager')->group(function() {
        Route::get('', "TeamController@index");
        Route::get('employes', 'TeamController@getEmployeesOfTeam');
        Route::patch('', "TeamController@updateTeamRequest");
    });

    // events
    Route::get('events', "EventController@index");

    // courses
    Route::prefix('courses')->group(function () {
        Route::get('', 'CourseController@index');
        Route::post('', 'CourseController@store');
    });

    //policy-documents
    Route::get('policy-documents', 'PolicyDocumentController@index');

    // it-tickets
    Route::group(['prefix' => 'it-tickets'], function() {
        Route::post('', 'ItTicketController@store');
        Route::get('', 'ItTicketController@index');
    });


});


