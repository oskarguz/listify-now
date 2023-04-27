<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecurityController;
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

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::controller(ChecklistController::class)->prefix('/checklist')->group(function () {
    Route::get('/create', 'create');
    Route::post('/create', 'store')->middleware('onlyXhr');
    Route::get('/show/{checklist}', 'show');
    Route::get('/update/{checklist}', 'edit');
    Route::post('/update/{checklist}', 'update')->middleware('onlyXhr');
    Route::post('/destroy/{checklist}', 'destroy');
});

Route::controller(ChecklistItemController::class)->prefix('/checklist-item')->group(function () {
    Route::post('/create', 'store');
    Route::post('/update/{checklistItem}', 'update');
    Route::post('/destroy/{checklistItem}', 'destroy');
})->middleware('onlyXhr');

Route::controller(SecurityController::class)->group(function () {
    Route::get('/login', 'login')->name('security.login');
    Route::get('/logout', 'logout')->name('security.logout');
    Route::get('/auth', 'authRedirect')->name('security.auth');
    Route::get('/auth/callback', 'authCallback')->name('security.callback');
});
