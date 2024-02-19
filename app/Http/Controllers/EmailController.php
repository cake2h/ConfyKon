<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class EmailController extends Controller
{
    public function sendEmails()
    {
        $users = User::all();

        foreach ($users as $user) {
            Mail::send(['text' => 'emails/mail'], ['name', 'Гындыбин Михаил Викторович'], function($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Приглашение на Всероссийскую конференцию молодых ученых "МАТЕМАТИЧЕСКОЕ И ИНФОРМАЦИОННОЕ МОДЕЛИРОВАНИЕ" (МИМ-2024)')
                        ->from('misamaikl97@gmail.com', 'Организатор конференции МИМ-2024')
                        ->attach(storage_path('app\Информационное письмо МИМ2024_ТюмГУ .pdf'));
            });
        }

        return redirect()->route('admin.index')->with('success', 'Приглашения успешно разосланы');;
    }
}

