<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('auth')->group(function () {
    Route::view('/', 'welcome');
    Route::view('/home', 'welcome');
});

Route::view('login', 'welcome')->name('login');

Route::view('demo', 'demo')->name('demo');

Route::fallback(function() {
    return view('welcome');
});
