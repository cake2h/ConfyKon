<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Model\Payment\PaymentMethodType;
use YooKassa\Model\Payment\ConfirmationType;
use YooKassa\Model\CurrencyCode;
use YooKassa\Model\Receipt\ReceiptCustomer;
use YooKassa\Model\Receipt\Receipt;
use YooKassa\Model\Receipt\ReceiptItem;
use YooKassa\Model\Receipt\ReceiptItemAmount;

class YooKassaService
{
    private Client $client;
    private string $shopId;
    private string $secretKey;

    public function __construct()
    {
        $this->shopId = env('YOOKASSA_SHOP_ID');
        $this->secretKey = env('YOOKASSA_SECRET_KEY');

        $this->client = new Client();
        $this->client->setAuth($this->shopId, $this->secretKey);
    }

   public function createPayment(User $user, float $amount, string $description, string $returnUrl = null): array
    {
        // Попробуем сначала создать локальный платеж
        try {
            $localPayment = Payment::create([
                'user_id' => $user->id,
                'type' => PaymentType::INCOME,
                'amount' => $amount,
                'status' => PaymentStatus::PENDING,
                'comment' => $description,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create local payment: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Не удалось создать локальный платеж',
            ];
        }

        // Подготавливаем данные для YooKassa
        $paymentData = [
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency' => CurrencyCode::RUB,
            ],
            'confirmation' => [
                'type' => ConfirmationType::REDIRECT,
                'return_url' => $returnUrl ?? route('dashboard.index'),
            ],
            'capture' => true,
            'description' => $description,
            'metadata' => [
                'user_id' => $user->id,
                'payment_type' => PaymentType::INCOME->value,
                'local_payment_id' => $localPayment->id,
            ],
        ];

        try {
            // Создаем платеж в YooKassa
            $payment = $this->client->createPayment($paymentData, uniqid('payment_'));

            return [
                'success' => true,
                'payment_id' => $payment->getId(),
                'confirmation_url' => $payment->getConfirmation()->getConfirmationUrl(),
                'local_payment_id' => $localPayment->id,
            ];
        } catch (\Exception $e) {
            // Удаляем локальный платеж, если YooKassa платеж не создан
            try {
                $localPayment->delete();
            } catch (\Exception $deleteError) {
                Log::warning('Failed to delete local payment after YooKassa failure: ' . $deleteError->getMessage());
            }

            Log::error('YooKassa payment creation error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Ошибка при создании платежа: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Создать чек для платежа
     */
    private function createReceipt(User $user, float $amount, string $description): Receipt
    {
        $receipt = new Receipt();
        
        // Информация о покупателе
        $customer = new ReceiptCustomer();
        $customer->setEmail($user->email);
        $receipt->setCustomer($customer);

        // Товар/услуга
        $receiptItem = new ReceiptItem();
        $receiptItem->setDescription($description);
        $receiptItem->setQuantity(1);
        // Убираем VatCode, так как он не найден в SDK
        
        $receiptItem->setPrice($amount);
        
        $receipt->addItem($receiptItem);

        return $receipt;
    }

    /**
     * Получить информацию о платеже
     */
    public function getPayment(string $paymentId): ?array
    {
        try {
            $payment = $this->client->getPaymentInfo($paymentId);
            
            return [
                'id' => $payment->getId(),
                'status' => $payment->getStatus(),
                'amount' => $payment->getAmount()->getValue(),
                'currency' => $payment->getAmount()->getCurrency(),
                'description' => $payment->getDescription(),
                'created_at' => $payment->getCreatedAt(),
                'paid' => $payment->getPaid(),
            ];
        } catch (\Exception $e) {
            Log::error('YooKassa get payment error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Обработать webhook от ЮKassa
     */
    public function handleWebhook(array $data): bool
    {
        try {
            $paymentId = $data['object']['id'] ?? null;
            $status = $data['object']['status'] ?? null;
            $userId = $data['object']['metadata']['user_id'] ?? null;

            if (!$paymentId || !$status || !$userId) {
                Log::error('Invalid webhook data from YooKassa');
                return false;
            }

            // Находим локальный платеж
            $localPayment = Payment::where('user_id', $userId)
                ->where('comment', 'like', '%' . $paymentId . '%')
                ->first();

            if (!$localPayment) {
                Log::error('Local payment not found for YooKassa payment: ' . $paymentId);
                return false;
            }

            // Обновляем статус платежа
            $paymentStatus = $this->mapYooKassaStatus($status);
            $localPayment->update(['status' => $paymentStatus]);

            // Если платеж успешен, обновляем баланс пользователя
            if ($paymentStatus === PaymentStatus::SUCCESS) {
                $user = User::find($userId);
                if ($user) {
                    $user->increment('balance', $localPayment->amount);
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('YooKassa webhook processing error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Маппинг статусов ЮKassa в наши статусы
     */
    private function mapYooKassaStatus(string $yooKassaStatus): PaymentStatus
    {
        return match($yooKassaStatus) {
            'pending' => PaymentStatus::PENDING,
            'waiting_for_capture' => PaymentStatus::PENDING,
            'succeeded' => PaymentStatus::SUCCESS,
            'canceled' => PaymentStatus::FAILED,
            default => PaymentStatus::PENDING,
        };
    }

    /**
     * Создать возврат платежа
     */
    public function createRefund(string $paymentId, float $amount, string $description = ''): array
    {
        try {
            $refund = $this->client->createRefund([
                'payment_id' => $paymentId,
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency' => CurrencyCode::RUB,
                ],
                'description' => $description,
            ], uniqid('refund_'));

            return [
                'success' => true,
                'refund_id' => $refund->getId(),
                'status' => $refund->getStatus(),
            ];

        } catch (\Exception $e) {
            Log::error('YooKassa refund creation error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
} 