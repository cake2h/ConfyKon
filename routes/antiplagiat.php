<?php

use App\Http\Controllers\AntiplagiatController;

Route::middleware('auth')->group(function () {
    Route::prefix('antiplagiat')->group(function () {
        Route::get('/upload', [AntiplagiatController::class, 'uploadForm'])->name('antiplagiat.upload.form');
        Route::post('/upload', [AntiplagiatController::class, 'upload'])->name('antiplagiat.upload');
        Route::get('/report/{docId}', [AntiplagiatController::class, 'report'])->name('antiplagiat.report');
    });
});
