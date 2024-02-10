<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;


Route::post('/send-emails', [EmailController::class, 'sendEmails'])->name('send.emails');

Route::prefix('')->group(function () {
    Route::get('/', [ConfController::class, 'index'])->name('conf.index');

    Route::prefix('conference')->group(function () {
        Route::get('/{conference}', [ConfController::class, 'show'])->name('conf.show');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard.index');
});

Route::prefix('admin')->middleware(['admin', 'auth'])->group(function () {
    Route::get('/main', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('conference')->group(function () {
        Route::get('/add', [ConfController::class, 'add'])->name('conf.add');
        Route::post('/add', [ConfController::class, 'store'])->name('conf.store');

        Route::get('/edit/{id}', [ConfController::class, 'edit'])->name('conf.edit');
        Route::post('/edit/{id}', [ConfController::class, 'update'])->name('conf.update');

        Route::delete('/delete/{id}', [ConfController::class, 'destroy'])->name('conf.destroy');

        
    });
});

require __DIR__.'/auth.php';
