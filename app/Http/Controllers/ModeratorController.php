<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;

class ModeratorController extends Controller
{
    public function index()
    {
        $moderator = auth()->user();

        $section = Section::where('moder_id', $moderator->id)->first();

        if (!$section) {
            return redirect()->back()->with('error', 'Секция не найдена для текущего модератора.');
        }

        $sectionName = $section->name;

        $applicants = Application::where('section_id', $section->id)
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('users.name', 'applications.name as work_name', 'applications.file_path', 'applications.id as application_id', 'applications.status')
            ->get();

        return view('moderator.moderator', compact('sectionName', 'applicants'));
    }

    public function approve($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);

        if ($application) {
            $application->status = 1;
            $application->save();

            $email = $user->email;

            Mail::send([], [], function($message) use ($email) {
                $message->to($email)
                    ->subject('Одобрение статьи на конференцию МИМ-2024')
                    ->from('stud0000264064@utmn.ru', 'Организатор конференции МИМ-2024')
                    ->text('Ваша статья одобрена');
            });
        }

        return redirect()->back()->with('success', 'Заявка одобрена.');
    }

    public function reject($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);

        if ($application) {
            $application->status = 2;
            $application->save();

            $email = $user->email;

            Mail::send([], [], function($message) use ($email) {
                $message->to($email)
                    ->subject('Отклонение статьи на конференцию МИМ-2024')
                    ->from('stud0000264064@utmn.ru', 'Организатор конференции МИМ-2024')
                    ->text('Ваша статья отклонена');
            });
        }

        return redirect()->back()->with('success', 'Заявка отклонена.');
    }
}
