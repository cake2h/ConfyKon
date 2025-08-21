# Интеграция с ЮKassa

## Обзор

Данная интеграция позволяет принимать платежи через платежную систему ЮKassa для пополнения баланса пользователей.

## Установка

### 1. Установка SDK

```bash
composer require yoomoney/yookassa-sdk-php
```

### 2. Конфигурация

Скопируйте переменные из `yookassa.env.example` в ваш `.env` файл:

```env
YOOKASSA_MODE=test
YOOKASSA_SHOP_ID=your_shop_id_here
YOOKASSA_SECRET_KEY=your_secret_key_here
YOOKASSA_CURRENCY=RUB
YOOKASSA_WEBHOOK_URL=/api/yookassa/webhook
YOOKASSA_WEBHOOK_ENABLED=true
YOOKASSA_REFUNDS_ENABLED=true
```

### 3. Получение данных для интеграции

1. Зарегистрируйтесь в [ЮKassa](https://yookassa.ru/)
2. Перейдите в раздел "Интеграция"
3. Получите Shop ID и Secret Key
4. Настройте webhook URL: `https://your-domain.com/api/yookassa/webhook`

## Использование

### Создание платежа

```php
use App\Services\YooKassaService;

$yooKassaService = new YooKassaService();
$result = $yooKassaService->createPayment(
    $user,
    1000.00, // сумма в рублях
    'Пополнение баланса',
    route('dashboard.index') // URL возврата
);

if ($result['success']) {
    // Перенаправляем на страницу оплаты
    return redirect($result['confirmation_url']);
}
```

### Обработка webhook

Webhook автоматически обрабатывается контроллером `PaymentController@webhook`.

### Получение статуса платежа

```php
$paymentInfo = $yooKassaService->getPayment($paymentId);
```

## API Endpoints

### Создание платежа
```
POST /payments/create
```

**Параметры:**
- `amount` (required) - сумма платежа
- `description` (optional) - описание платежа

### Получение статуса платежа
```
GET /payments/status?payment_id={id}
```

### Webhook
```
POST /api/yookassa/webhook
```

### Страницы возврата
```
GET /payments/success
GET /payments/cancel
```

## Структура базы данных

### Таблица payments
- `id` - ID платежа
- `user_id` - ID пользователя
- `type` - тип платежа (enum: INCOME/EXPENSE)
- `amount` - сумма платежа
- `status` - статус платежа (enum: PENDING/SUCCESS/FAILED)
- `comment` - комментарий к платежу
- `created_at` - дата создания
- `updated_at` - дата обновления

### Таблица users
- `balance` - баланс пользователя

## Тестирование

### Тестовые карты ЮKassa

Для тестирования используйте следующие карты:

- **Успешная оплата:** 1111 1111 1111 1026, 12/25, 123
- **Недостаточно средств:** 1111 1111 1111 1047, 12/25, 123
- **Карта заблокирована:** 1111 1111 1111 1100, 12/25, 123

### Тестовые данные

```php
// Создание тестового платежа
$result = $yooKassaService->createPayment(
    $user,
    100.00,
    'Тестовое пополнение'
);
```

## Безопасность

1. **Валидация webhook** - проверяйте подпись webhook от ЮKassa
2. **HTTPS** - используйте только HTTPS для webhook
3. **Логирование** - все операции логируются
4. **Обработка ошибок** - все исключения обрабатываются

## Логирование

Все операции с ЮKassa логируются в `storage/logs/laravel.log`:

```php
Log::info('YooKassa payment created', ['payment_id' => $paymentId]);
Log::error('YooKassa payment failed', ['error' => $error]);
```

## Поддержка

- [Документация ЮKassa](https://yookassa.ru/developers)
- [API Reference](https://yookassa.ru/developers/api)
- [Тестирование](https://yookassa.ru/developers/using-api/testing) 