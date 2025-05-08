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

        $sections = Section::where('moder_id', $moderator->id)->get();

        if ($sections->isEmpty()) {
            return view('moderator.moderator', [
                'error' => 'Вы не являетесь модератором ни одной секции.'
            ]);
        }

        $applicants = Application::whereIn('section_id', $sections->pluck('id'))
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('sections', 'applications.section_id', '=', 'sections.id')
            ->select(
                'users.name as user_name',
                'applications.name as work_name',
                'applications.file_path',
                'applications.id as application_id',
                'applications.status',
                'sections.name as section_name'
            )
            ->get();

        return view('moderator.moderator', compact('sections', 'applicants'));
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
