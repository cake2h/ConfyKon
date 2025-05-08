<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Conf;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = User::with(['applications.section.konf', 'applications.role'])->find(auth()->id());

        $conferences = Conf::with('conferenceDates')->get();
        $currentDate = now();
        $conferenceDates = Conf::select('date_end', 'deadline')->first();

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

    /**
     * @param \Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_User_C $users
     * @return array
     */
}

