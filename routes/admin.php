<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|-----------------------------
| auth
|-----------------------------
 */
Route::prefix('/auth')->group(function(){

    Route::get('login', function(){
        return 'login';
    });

    
});