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
    Route::get('/{checklist}', 'show');
    Route::post('/{checklist}/update', 'update')->middleware('onlyXhr');
    Route::post('/{checklist}/destroy', 'destroy');
});

Route::controller(ChecklistItemController::class)->prefix('/checklist/{checklist}/items')->group(function () {
    Route::post('/create', 'store');
    Route::post('/{checklistItem}/update', 'update');
    Route::post('/{checklistItem}/destroy', 'destroy');
})->middleware('onlyXhr');

Route::controller(SecurityController::class)->group(function () {
    Route::get('/login', 'login')->name('security.login');
    Route::get('/logout', 'logout')->name('security.logout');
    Route::get('/auth', 'authRedirect')->name('security.auth');
    Route::get('/auth/callback', 'authCallback')->name('security.callback');
});
