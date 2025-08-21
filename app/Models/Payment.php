<?php

namespace App\Models;

use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'comment'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'type' => PaymentType::class,
        'status' => PaymentStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Получить пользователя, связанного с платежом
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить тип операции на русском языке
     */
    public function getTypeLabelAttribute()
    {
        return $this->type ? $this->type->label() : 'Неизвестно';
    }

    /**
     * Получить статус на русском языке
     */
    public function getStatusLabelAttribute()
    {
        return $this->status ? $this->status->label() : 'Неизвестно';
    }

    /**
     * Получить сумму с префиксом (+ или -)
     */
    public function getFormattedAmountAttribute()
    {
        $prefix = $this->type === PaymentType::INCOME ? '+' : '-';
        return $prefix . ' ' . number_format($this->amount, 2) . ' руб.';
    }

    /**
     * Получить CSS класс для типа платежа
     */
    public function getTypeClassAttribute()
    {
        return $this->type === PaymentType::INCOME ? 'income' : 'expense';
    }

    /**
     * Получить CSS класс для статуса платежа
     */
    public function getStatusClassAttribute()
    {
        return match($this->status) {
            PaymentStatus::SUCCESS => 'success',
            PaymentStatus::PENDING => 'pending',
            PaymentStatus::FAILED => 'failed',
            default => 'unknown'
        };
    }
}
