<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Conference;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = User::with([
            'applications' => function($query) {
                $query->where('application_status_id', '!=', 2);
            },
            'applications.section.conference',
            'applications.role',
            'applications.report.reportStatus',
            'applications.report.reportComments',
            'city',
            'education_level',
            'study_place'
        ])->find(auth()->id());

        $conferences = Conference::all();
        $currentDate = now();
        $conferenceDates = Conference::select('date_end', 'deadline_applications')->first();

        return view('dashboard', compact('user', 'conferences', 'currentDate', 'conferenceDates'));
    }

    public function exportUsers()
    {
        // Получаем количество типов выступлений (очных/заочных)
        $presentationCounts = Application::selectRaw('presentation_types.name as type, count(applications.id) as count')
            ->join('presentation_types', 'applications.type_id', '=', 'presentation_types.id')
            ->groupBy('type')
            ->get();

        // Получаем количество иногородних участников
        $nonLocalParticipants = User::where('city', '<>', 'Тюмень')->count();

        // Получаем количество пользователей на каждой секции
        $sections = Section::withCount('users')->get();

        // Экспортируем пользователей и статистику в Excel
        return Excel::download(new UsersExport($presentationCounts, $nonLocalParticipants, $sections), 'статистика.xlsx');
    }

    public function edit()
    {
        $user = auth()->user();
        $cities = \App\Models\City::all();
        $educationLevels = \App\Models\EducationLevel::all();
        $studyPlaces = \App\Models\StudyPlace::all();
        return view('profile.edit', compact('user', 'cities', 'educationLevels', 'studyPlaces'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:25',
            'birthday' => 'required|date',
            'city_id' => 'required|exists:cities,id',
            'education_level_id' => 'required|exists:education_levels,id',
            'study_place_id' => 'required|exists:study_places,id',
        ]);
        $user->update($validated);
        return redirect()->route('dashboard.index')->with('success', 'Профиль успешно обновлён!');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_User_C $users
     * @return array
     */
}

