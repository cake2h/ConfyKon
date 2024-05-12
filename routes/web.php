<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {
    Route::get('/', [ConfController::class, 'index'])->name('conf.index');

    Route::prefix('conference')->group(function () {
        Route::get('/{conference}', [ConfController::class, 'show'])->name('conf.show');
        Route::get('/{conference}/sections', [ConfController::class, 'getSections'])->name('conf.sections');
        Route::post('/{conference}/subs', [ConfController::class, 'subscribe'])->name('conf.subscribe');
    });
});

Route::post('/dock', [ConfController::class, 'dock'])->name('conf.dock');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard.index');
});

Route::prefix('admin')->middleware(['admin', 'auth'])->group(function () {
    Route::get('/main', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/export-users', [ProfileController::class, 'exportUsers'])->name('export_all_users');

    Route::get('/page-emails', [EmailController::class, 'emailsPage'])->name('page.emails');
    Route::post('/send-emails', [EmailController::class, 'sendEmails'])->name('send.emails');
    Route::post('/save-mail', [EmailController::class, 'saveMail'])->name('save.mail');


    Route::prefix('conference')->group(function () {
        Route::get('/add', [ConfController::class, 'add'])->name('conf.add');
        Route::post('/add', [ConfController::class, 'store'])->name('conf.store');

        Route::get('/edit/{id}', [ConfController::class, 'edit'])->name('conf.edit');
        Route::post('/edit/{id}', [ConfController::class, 'update'])->name('conf.update');

        Route::delete('/delete/{id}', [ConfController::class, 'destroy'])->name('conf.destroy');

        Route::prefix('{conference}/sections')->group(function () {
            Route::get('/',  [SectionController::class, 'adminSections'])->name('admin.sections.index');

            Route::get('/add', [SectionController::class, 'add'])->name('admin.sections.add');
            Route::post('/add', [SectionController::class, 'store'])->name('admin.sections.store');

            Route::get('/{section}/edit', [SectionController::class, 'edit'])->name('admin.sections.edit');
            Route::put('/{section}/edit', [SectionController::class, 'update'])->name('admin.sections.update');


            Route::delete('/{section}/destroy', [SectionController::class, 'destroy'])->name('admin.sections.destroy');

        });
    });
});

require __DIR__.'/auth.php';
