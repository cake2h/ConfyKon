<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ConfController::class, 'index'])->name('conf.index');

Route::middleware('auth')->group(function () {
    Route::get('/lk', [ProfileController::class, 'lk'])->name('lk');
});

Route::prefix('admin')->middleware(['admin', 'auth'])->group(function () {
    Route::get('/main', [AdminController::class, 'index'])->name('admin.index');

    Route::prefix('conf')->group(function () {
        Route::get('/add', [ConfController::class, 'add'])->name('conf.add');
        Route::post('/add', [ConfController::class, 'store'])->name('conf.store');

        Route::get('/edit/{id}', [ConfController::class, 'edit'])->name('conf.edit');
        Route::post('/edit/{id}', [ConfController::class, 'update'])->name('conf.update');

        Route::get('/delete/{id}', [ConfController::class, 'destroy'])->name('conf.destroy');
    });
    
});

Route::get('/ajax', [ProfileController::class, 'ajax'])->name('ajax.page');

require __DIR__.'/auth.php';
