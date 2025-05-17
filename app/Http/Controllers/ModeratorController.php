<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class ModeratorController extends Controller
{
    public function index()
    {
        $moderator = auth()->user();

        $sections = Section::where('user_id', $moderator->id)->get();

        if ($sections->isEmpty()) {
            return view('moderator.moderator', [
                'error' => 'Вы не являетесь модератором ни одной секции.'
            ]);
        }

        // Получаем SQL-запрос для отладки
        $activeQuery = Application::whereIn('section_id', $sections->pluck('id'))
            ->where('applications.application_status_id', '!=', 2)
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('sections', 'applications.section_id', '=', 'sections.id')
            ->leftJoin('reports', 'applications.report_id', '=', 'reports.id')
            ->leftJoin('participation_types', 'applications.participation_type_id', '=', 'participation_types.id')
            ->leftJoin('presentation_types', 'applications.presentation_type_id', '=', 'presentation_types.id')
            ->leftJoin('education_levels', 'users.education_level_id', '=', 'education_levels.id')
            ->leftJoin('study_places', 'users.study_place_id', '=', 'study_places.id')
            ->select(
                'users.surname',
                'users.name',
                'users.patronymic',
                'reports.report_theme',
                'applications.id as application_id',
                'applications.application_status_id',
                'sections.name as section_name',
                'participation_types.name as participation_type_name',
                'presentation_types.name as presentation_type_name',
                'education_levels.name as education_level_name',
                'study_places.name as study_place_name'
            );

        $rejectedQuery = Application::whereIn('section_id', $sections->pluck('id'))
            ->where('applications.application_status_id', '=', 2)
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('sections', 'applications.section_id', '=', 'sections.id')
            ->leftJoin('reports', 'applications.report_id', '=', 'reports.id')
            ->leftJoin('participation_types', 'applications.participation_type_id', '=', 'participation_types.id')
            ->leftJoin('presentation_types', 'applications.presentation_type_id', '=', 'presentation_types.id')
            ->leftJoin('education_levels', 'users.education_level_id', '=', 'education_levels.id')
            ->leftJoin('study_places', 'users.study_place_id', '=', 'study_places.id')
            ->select(
                'users.surname',
                'users.name',
                'users.patronymic',
                'reports.report_theme',
                'applications.id as application_id',
                'applications.application_status_id',
                'sections.name as section_name',
                'participation_types.name as participation_type_name',
                'presentation_types.name as presentation_type_name',
                'education_levels.name as education_level_name',
                'study_places.name as study_place_name'
            );

        $activeApplicants = $activeQuery->get();
        $rejectedApplicants = $rejectedQuery->get();

        // Добавляем отладочную информацию
        // \Log::info('Active applicants count: ' . $activeApplicants->count());
        // \Log::info('Rejected applicants count: ' . $rejectedApplicants->count());
        // \Log::info('Active SQL: ' . $activeQuery->toSql());
        // \Log::info('Rejected SQL: ' . $rejectedQuery->toSql());
        // \Log::info('Section IDs: ' . $sections->pluck('id')->implode(', '));

        return view('moderator.moderator', compact('sections', 'activeApplicants', 'rejectedApplicants'));
    }

    public function approve($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);

        if ($application) {
            // Обновляем статус доклада
            if ($application->report) {
                $application->report->report_status_id = 2; // Одобрено
                $application->report->save();
            }

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
        $application = Application::findOrFail($id);
        $user = User::find($application->user_id);

        if ($application) {
            // Обновляем статус доклада
            if ($application->report) {
                $application->report->report_status_id = 3; // Отклонено
                $application->report->save();

                // Сохраняем комментарий
                DB::table('report_comments')->insert([
                    'comment' => request('comment'),
                    'report_id' => $application->report->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

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

    public function reports($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        
        // Проверяем, является ли пользователь модератором этой секции
        if ($section->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав на просмотр этой секции.');
        }

        $applicants = Application::where('section_id', $sectionId)
            ->where('applications.application_status_id', '!=', 2)
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->join('reports', 'applications.report_id', '=', 'reports.id')
            ->join('report_statuses', 'reports.report_status_id', '=', 'report_statuses.id')
            ->select(
                'users.name as user_name',
                'reports.report_theme as work_name',
                'reports.file_path',
                'applications.id as application_id',
                'report_statuses.name as report_status'
            )
            ->get();

        return view('moderator.reports', compact('section', 'applicants'));
    }

    public function rejectApplication($id)
    {
        $application = Application::findOrFail($id);
        
        // Проверяем, является ли пользователь модератором секции
        if ($application->section->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав на отклонение этой заявки.');
        }

        $application->application_status_id = 2; // Отклонено
        $application->save();

        return redirect()->back()->with('success', 'Заявка отклонена.');
    }

    public function restoreApplication($id)
    {
        $application = Application::findOrFail($id);
        
        // Проверяем, является ли пользователь модератором секции
        if ($application->section->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'У вас нет прав на восстановление этой заявки.');
        }

        $application->application_status_id = 1; // Активно
        $application->save();

        return redirect()->back()->with('success', 'Заявка восстановлена.');
    }
}
