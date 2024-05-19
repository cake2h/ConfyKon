<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\EmailsForSend;
use Illuminate\Support\Facades\File;

class EmailController extends Controller
{
    public function emailsPage()
    {
        $users = User::all();

        return view('emails.email_page', compact('users'));
    }

    public function saveMail(Request $request)
    {
        $text = $request->input('mail_text');
        $path = resource_path('views/emails/mail.blade.php');
        File::put($path, $text);

        return redirect()->back()->with('message', 'Письмо успешно сохранено.');
    }

    public function sendEmails(Request $request)
    {
        ini_set('max_execution_time', 3600);

        $emailList = request('emails');
        $emails = explode("\n", $emailList);

        foreach ($emails as $email) {
            Mail::send(['text' => 'emails/mail'], ['name' => ''], function($message) use ($email) {
                $message->to($email)
                    ->subject('Приглашение на Всероссийскую конференцию молодых ученых "МАТЕМАТИЧЕСКОЕ И ИНФОРМАЦИОННОЕ МОДЕЛИРОВАНИЕ" (МИМ-2024)')
                    ->from('stud0000264064@utmn.ru', 'Организатор конференции МИМ-2024');
            });

            set_time_limit(0);
        }

        return redirect()->back()->with('success', 'Приглашения успешно разосланы');
    }

    public function getEmails(Request $request)
    {
        switch ($request->input('type')) {
            case 'conference':
                $users = User::whereHas('applications')->get();
                break;
            case 'moderators':
                $users = User::where('role', 'moderator')->get();
                break;
            default:
                $users = User::all();
        }

        return response()->json($users->map(function($user) {
            return "{$user->email}";
        }));
    }
}


