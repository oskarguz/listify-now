<?php

use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecurityController;
use App\Models\Checklist;
use App\Models\ChecklistItem;
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

Route::get('/dashboard', DashboardController::class)->name('dashboard');

Route::controller(ChecklistController::class)->prefix('/checklist')->group(function () {
    Route::get('/create', 'create')
        ->can('create', Checklist::class);

    Route::post('/create', 'store')
        ->middleware('onlyXhr')
        ->can('create', Checklist::class);

    Route::get('/{checklist}', 'show')
        ->can('view', 'checklist');

    Route::post('/{checklist}/update', 'update')
        ->middleware('onlyXhr')
        ->can('update', 'checklist');

    Route::post('/{checklist}/destroy', 'destroy')
        ->can('delete', 'checklist');
});

Route::controller(ChecklistItemController::class)->prefix('/checklist/{checklist}/items')->group(function () {
    Route::post('/create', 'store')
        ->can('create', [ChecklistItem::class, 'checklist']);

    Route::post('/{checklistItem}/update', 'update')
        ->can('update', [ChecklistItem::class, 'checklist', 'checklistItem']);

    Route::post('/{checklistItem}/destroy', 'destroy')
        ->can('delete', [ChecklistItem::class, 'checklist', 'checklistItem']);

})->middleware('onlyXhr');

Route::controller(SecurityController::class)->group(function () {
    Route::get('/login', 'login')->name('security.login');
    Route::get('/logout', 'logout')->name('security.logout');
    Route::get('/auth', 'authRedirect')->name('security.auth');
    Route::get('/auth/callback', 'authCallback')->name('security.callback');
});

Route::get('/check-user', function () {
    $user = Auth::user();
    if (!$user) {
        return abort(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
    }

    return response()->json($user);
});
