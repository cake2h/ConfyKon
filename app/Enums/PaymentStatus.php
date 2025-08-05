<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = 1;
    case SUCCESS = 2;
    case FAILED = 3;

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'В обработке',
            self::SUCCESS => 'Успешно',
            self::FAILED => 'Ошибка',
        };
    }

    public function code(): string
    {
        return match($this) {
            self::PENDING => 'pending',
            self::SUCCESS => 'success',
            self::FAILED => 'failed',
        };
    }
}