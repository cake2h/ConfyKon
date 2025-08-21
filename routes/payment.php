<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Маршруты для платежей (требуют аутентификации)
Route::middleware('auth')->group(function () {
    // Создание платежа
    Route::post('/payments/create', [PaymentController::class, 'createPayment'])->name('payments.create');
    
    // Получение статуса платежа
    Route::get('/payments/status', [PaymentController::class, 'getPaymentStatus'])->name('payments.status');
    
    // Создание возврата
    Route::post('/payments/refund', [PaymentController::class, 'createRefund'])->name('payments.refund');
});

// Webhook от ЮKassa (не требует аутентификации)
Route::post('/api/yookassa/webhook', [PaymentController::class, 'webhook'])->name('yookassa.webhook');

// Страницы успеха и отмены платежа
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');
