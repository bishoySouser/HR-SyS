<?php

use App\Http\Controllers\Api\V1\Auth\User;
use Illuminate\Support\Facades\Route;

// Route::post('register',[User::class,'register']);
Route::post('login',[User::class,'login']);
Route::post('logout',[User::class,'logout'])->middleware('auth:sanctum');


Route::group(['prefix' => 'employees', 'middleware' => 'auth:sanctum'], function() {
    Route::apiResource('', EmployeeController::class);
    Route::get('profile', 'EmployeeController@getProfile');
});

Route::prefix('courses')->group(function () {
    Route::get('', 'CourseController@index');
    Route::post('', 'CourseController@store')->middleware('auth:sanctum');
});


