<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/addkonf', function () {
    return view('addkonf');
})->name('addkonf');

Route::get('/teams', [TeamController::class, 'index'])->name('team.index');
Route::post('/create', [TeamController::class, 'create'])->name('team.create');
Route::get('/join', [TeamController::class, 'index'])->name('team.join');

Route::get('/', [ProfileController::class, 'index'])->name('konf.index');
Route::post('/addkonff', [ProfileController::class, 'store'])->name('konf.store');
Route::post('/regkonf/{id}', [ProfileController::class, 'reg'])->name('konf.reg');

Route::middleware('auth')->group(function () {
    Route::get('/lk', [ProfileController::class, 'lk'])->name('lk');
    Route::get('/delete/{id}', [ProfileController::class, 'delete'])->name('delete');
    Route::get('/konf/{id}', [ProfileController::class, 'updatekonf'])->name('updatekonf');
    Route::post('/up/{id}', [ProfileController::class, 'upkon'])->name('upkon');
});

Route::middleware(['admin', 'auth'])->group(function () {
    Route::get('/admin/main', [ProfileController::class, 'admin'])->name('admin.page');
});

Route::get('/ajax', [ProfileController::class, 'ajax'])->name('ajax.page');

Route::get('/subscribe/{id}', [ProfileController::class, 'subscribe'])->name('subscribe');
require __DIR__.'/auth.php';
