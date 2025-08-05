<?php

namespace App\Http\Controllers;

use App\Services\YooKassaService;
use App\Models\Payment;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private YooKassaService $yooKassaService;

    public function __construct(YooKassaService $yooKassaService)
    {
        $this->yooKassaService = $yooKassaService;
    }

    /**
     * Создать платеж для пополнения баланса
     */
    public function createPayment(Request $request): JsonResponse
    {   
        try {
            Log::info('Payment creation request received', $request->all());

            $request->validate([
                'amount' => 'required|numeric|min:1|max:100000',
                'description' => 'nullable|string|max:255',
            ]);

            $user = auth()->user();
            $amount = (float) $request->amount;
            $description = $request->description ?? "Пополнение баланса на {$amount} руб.";

            Log::info('Calling YooKassa service', [
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => $description
            ]);

            $result = $this->yooKassaService->createPayment(
                $user,
                $amount,
                $description,
                route('dashboard.index')
            );

            Log::info('YooKassa service result', $result);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'payment_id' => $result['payment_id'],
                    'confirmation_url' => $result['confirmation_url'],
                    'local_payment_id' => $result['local_payment_id'],
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment controller error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Внутренняя ошибка сервера: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Получить статус платежа
     */
    public function getPaymentStatus(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|string',
        ]);

        $paymentId = $request->payment_id;
        $paymentInfo = $this->yooKassaService->getPayment($paymentId);

        if (!$paymentInfo) {
            return response()->json([
                'success' => false,
                'error' => 'Платеж не найден',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'payment' => $paymentInfo,
        ]);
    }

    /**
     * Webhook для получения уведомлений от ЮKassa
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            
            // Логируем webhook для отладки
            Log::info('YooKassa webhook received', $data);

            // Проверяем, что это уведомление о платеже
            if (!isset($data['event']) || $data['event'] !== 'payment.succeeded') {
                return response()->json(['success' => true]);
            }

            // Обрабатываем webhook
            $result = $this->yooKassaService->handleWebhook($data);

            if ($result) {
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'error' => 'Webhook processing failed'], 500);

        } catch (\Exception $e) {
            Log::error('YooKassa webhook error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Страница успешного платежа
     */
    public function success(Request $request)
    {
        $paymentId = $request->get('payment_id');
        
        if ($paymentId) {
            $paymentInfo = $this->yooKassaService->getPayment($paymentId);
            
            if ($paymentInfo && $paymentInfo['paid']) {
                return redirect()->route('dashboard.index')
                    ->with('success', 'Платеж успешно выполнен! Ваш баланс пополнен.');
            }
        }

        return redirect()->route('dashboard.index')
            ->with('error', 'Ошибка при обработке платежа.');
    }

    /**
     * Страница неуспешного платежа
     */
    public function cancel(Request $request)
    {
        return redirect()->route('dashboard.index')
            ->with('error', 'Платеж был отменен.');
    }

    /**
     * Создать возврат платежа
     */
    public function createRefund(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $result = $this->yooKassaService->createRefund(
            $request->payment_id,
            (float) $request->amount,
            $request->description ?? 'Возврат платежа'
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'refund_id' => $result['refund_id'],
                'status' => $result['status'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 400);
    }
} 