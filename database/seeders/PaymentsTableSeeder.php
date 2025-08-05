<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Получаем первого пользователя для тестовых данных
        $user = User::first();
        
        if (!$user) {
            return;
        }

        // Создаем тестовые платежи
        Payment::create([
            'user_id' => $user->id,
            'type' => PaymentType::EXPENSE,
            'amount' => 52.00,
            'status' => PaymentStatus::SUCCESS,
            'comment' => 'Оплата за создание конференции "Математическое и информационное моделирование"',
            'created_at' => Carbon::parse('2025-07-08 09:13:49'),
            'updated_at' => Carbon::parse('2025-07-08 09:13:49'),
        ]);

        Payment::create([
            'user_id' => $user->id,
            'type' => PaymentType::INCOME,
            'amount' => 52.00,
            'status' => PaymentStatus::SUCCESS,
            'comment' => '',
            'created_at' => Carbon::parse('2025-08-08 09:15:49'),
            'updated_at' => Carbon::parse('2025-08-08 09:15:49'),
        ]);

        // Обновляем баланс пользователя
        $user->update(['balance' => 28908.40]);
    }
} 