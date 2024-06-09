<?php

use App\Http\Controllers\Api\V1\Auth\User;
use Illuminate\Support\Facades\Route;

// Route::post('register',[User::class,'register']);
Route::post('login',[User::class,'login']);
Route::post('logout',[User::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    // employees
    Route::group(['prefix' => 'employees'], function() {
        Route::apiResource('', EmployeeController::class);
        Route::get('profile', 'EmployeeController@getProfile');
    });

    // excueses
    Route::prefix('excuses')->group(function() {
        Route::post('', "ExcuseController@store");
        Route::get('', "ExcuseController@index");
    });

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


