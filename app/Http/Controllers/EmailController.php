<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailsForSend;


class EmailController extends Controller
{
    public function sendEmails()
    {
        ini_set('max_execution_time', 3600);
        $users = User::all();

        foreach ($users as $user) {
            Mail::send(['text' => 'emails/mail'], ['name', ''], function($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Приглашение на Всероссийскую конференцию молодых ученых "МАТЕМАТИЧЕСКОЕ И ИНФОРМАЦИОННОЕ МОДЕЛИРОВАНИЕ" (МИМ-2024)')
                        ->from('l.n.bakanovskaya@utmn.ru', 'Организатор конференции МИМ-2024')
                        ->attach(storage_path('app\Информационное письмо МИМ2024_ТюмГУ .pdf'));
            });

            set_time_limit(0);
        }

        return redirect()->route('admin.index')->with('success', 'Приглашения успешно разосланы');;
    }

    public function sendInviteEmails()
    {
        ini_set('max_execution_time', 3600);

        $emails = EmailsForSend::pluck('email')->toArray();

        foreach ($emails as $email) {
            Mail::send(
                ['text' => 'emails/mail'],
                ['name' => ''],
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Приглашение на Всероссийскую конференцию молодых ученых "МАТЕМАТИЧЕСКОЕ И ИНФОРМАЦИОННОЕ МОДЕЛИРОВАНИЕ" (МИМ-2024)')
                        ->from('l.n.bakanovskaya@utmn.ru', 'Организатор конференции МИМ-2024')
                        ->attach(storage_path('app\Информационное письмо МИМ2024_ТюмГУ .pdf'));
                }
            );
            set_time_limit(0);
        }

        return redirect()->route('admin.index')->with('success', 'Приглашения успешно разосланы');
    }
}

