<?php

namespace App\Enums;

enum PaymentType: int
{
    case INCOME = 1;
    case EXPENSE = 2;

    public function label(): string
    {
        return match($this) {
            self::INCOME => 'Пополнение',
            self::EXPENSE => 'Списание',
        };
    }

    public function code(): string
    {
        return match($this) {
            self::INCOME => 'income',
            self::EXPENSE => 'expense',
        };
    }
} 