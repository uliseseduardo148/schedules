<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('schedules', ScheduleController::class);

Route::get('/schedules', [ScheduleController::class, 'index']);

Route::get('/show-schedules', [ScheduleController::class, 'show']);
Route::get('/report-schedules', [ScheduleController::class, 'report']);
Route::get('/show-availabity', [ScheduleController::class, 'reportByDates']);
