<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = url('reset-password', $this->token);
        return (new MailMessage)
            ->greeting('Здравствуйте!')
            ->line('Сброс пароля для всероссийской конференции молодых ученых "МАТЕМАТИЧЕСКОЕ И ИНФОРМАЦИОННОЕ МОДЕЛИРОВАНИЕ".')
            ->action('Сбросить пароль', $resetUrl)
            ->line('Если вы не запрашивали сброс пароля, никакие действия не требуются')
            ->line('Если у вас возникли проблемы с нажатием кнопки "Сбросить пароль", скопируйте и вставьте URL ниже в адресную строку вашего веб-браузера:')
            ->line($resetUrl)
            ->salutation('С уважением, Laravel');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
