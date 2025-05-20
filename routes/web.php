<?php

use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PythonController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\AiHelpController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;


Route::prefix('')->group(function () {
    Route::get('/', [ConfController::class, 'index'])->name('conf.index');

    Route::prefix('conference')->group(function () {
        Route::get('/{conference}', [ConfController::class, 'show'])->name('conf.show');
        Route::get('/{conference}/sections', [ConfController::class, 'showSections'])->name('conf.sections.show');
        Route::get('/{conference}/sections/list', [ConfController::class, 'getSections'])->name('conf.sections.list');
        Route::post('/{conference}/subscribe', [ConfController::class, 'subscribe'])->name('conf.subscribe');
        Route::get('/conference}/search', [ConfController::class, 'search'])->name('conference.search');
        Route::get('/{conference}/faq', [ConfController::class, 'showFAQ'])->name('conf.faq');
    });
});

Route::post('/dock', [ConfController::class, 'dock'])->name('conf.dock');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard.index');
});

Route::prefix('moder')->middleware('moder')->group(function () {
    Route::get('/mainModerator', [ModeratorController::class, 'index'])->name('moderator.index');
    Route::post('/application/approve/{id}', [ModeratorController::class, 'approve'])->name('application.approve');
    Route::post('/application/reject/{id}', [ModeratorController::class, 'reject'])->name('application.reject');
});

Route::prefix('admin')->middleware(['admin', 'auth'])->group(function () {
    Route::get('/main', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/emails', [EmailController::class, 'emailsPage'])->name('page.emails');
    Route::post('/save-mail', [EmailController::class, 'saveMail'])->name('save.mail');
    Route::post('/send-emails', [EmailController::class, 'sendEmails'])->name('send.emails');
    Route::post('/get-emails', [EmailController::class, 'getEmails'])->name('get.emails');

    Route::get('/stats', [ProfileController::class, 'exportUsers'])->name('stats');

    Route::get('/konfs/create', [App\Http\Controllers\Admin\KonfController::class, 'create'])->name('admin.konfs.create');
    Route::post('/konfs', [App\Http\Controllers\Admin\KonfController::class, 'store'])->name('admin.konfs.store');
    Route::get('/konfs/{konf}/edit', [App\Http\Controllers\Admin\KonfController::class, 'edit'])->name('admin.konfs.edit');
    Route::put('/konfs/{konf}', [App\Http\Controllers\Admin\KonfController::class, 'update'])->name('admin.konfs.update');
    Route::delete('/konfs/{konf}', [App\Http\Controllers\Admin\KonfController::class, 'destroy'])->name('admin.konfs.destroy');
    Route::get('/konfs/{konf}', [App\Http\Controllers\Admin\KonfController::class, 'downloadPDF'])->name('admin.konfs.donwloadPDF');

    Route::prefix('{conference}/sections')->group(function () {
        // Route::get('/',  [SectionController::class, 'adminSections'])->name('admin.sections.index');
        Route::get('/add', [SectionController::class, 'add'])->name('admin.sections.add');
        Route::post('/add', [SectionController::class, 'store'])->name('admin.sections.store');
        Route::get('/{section}/edit', [SectionController::class, 'edit'])->name('admin.sections.edit');
        Route::put('/{section}/edit', [SectionController::class, 'update'])->name('admin.sections.update');
        Route::delete('/{section}/destroy', [SectionController::class, 'destroy'])->name('admin.sections.destroy');
    });

    Route::get('/konfs/faq/{themeId}', [App\Http\Controllers\Admin\KonfController::class, 'showFaqForm'])->name('admin.konfs.faq');
    Route::post('/konfs/faq/{themeId}', [App\Http\Controllers\Admin\KonfController::class, 'storeFaq'])->name('admin.konfs.store.faq');
    Route::get('/konfs/{konf}/faq', [App\Http\Controllers\Admin\KonfController::class, 'editFaq'])->name('admin.konfs.edit.faq');
    Route::post('/konfs/{konf}/faq', [App\Http\Controllers\Admin\KonfController::class, 'updateFaq'])->name('admin.konfs.update.faq');
    Route::delete('/konfs/{konf}/faq/{faq}', [App\Http\Controllers\Admin\KonfController::class, 'deleteFaq'])->name('admin.konfs.delete.faq');

    Route::get('/konfs/{id}/download-file', [App\Http\Controllers\Admin\KonfController::class, 'downloadFile'])->name('admin.konfs.download-file');
});

Route::get('/conferences/{conference}/sections', [ConfController::class, 'getSections'])->name('conferences.sections');
Route::get('/applications/{id}/edit', [ConfController::class, 'editApplication'])->name('conf.edit_application');
Route::put('/applications/{id}', [ConfController::class, 'updateApplication'])->name('conf.update_application');
Route::delete('/applications/{id}', [ConfController::class, 'deleteApplication'])->name('conf.delete_application');

Route::get('/cities/search', [CityController::class, 'search'])->name('cities.search');

Route::middleware(['auth', 'moder'])->group(function () {
    Route::get('/moderator', [ModeratorController::class, 'index'])->name('moderator.index');
    Route::post('/application/{id}/approve', [ModeratorController::class, 'approve'])->name('application.approve');
    Route::post('/application/{id}/reject', [ModeratorController::class, 'reject'])->name('application.reject');
    Route::get('/moderator/reports/{section}', [ModeratorController::class, 'reports'])->name('moderator.reports');
    Route::post('/application/{id}/reject-application', [ModeratorController::class, 'rejectApplication'])->name('application.reject-application');
    Route::post('/application/{id}/restore', [ModeratorController::class, 'restoreApplication'])->name('application.restore-application');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/ai-help', [AiHelpController::class, 'index'])->name('ai.help');
    Route::post('/ai-help/analyze', [AiHelpController::class, 'analyze'])->name('ai.analyze');
});

require __DIR__.'/auth.php';
