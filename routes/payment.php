<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/payments/create', [PaymentController::class, 'createPayment'])->name('payments.create');
    Route::get('/payments/status', [PaymentController::class, 'getPaymentStatus'])->name('payments.status');
    Route::post('/payments/refund', [PaymentController::class, 'createRefund'])->name('payments.refund');
});

Route::post('/api/yookassa/webhook', [PaymentController::class, 'webhook'])->name('yookassa.webhook');

Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
