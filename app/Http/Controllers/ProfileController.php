<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Conf;
use App\Models\KonfUser;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = User::find(auth()->id());

        $conferences = Conf::with('conferenceDates')->get();
        $currentDate = now();
        $conferenceDates = Conf::select('date_end', 'deadline')->first();

        return view('dashboard', compact('user', 'conferences', 'currentDate', 'conferenceDates'));
    }

    public function exportUsers(Request $request)
    {
        $request->validate([
            'section_id' => ['required', 'exists:sections,id'],
        ]);

        $sectionId = $request->input('section_id');

        $users = User::whereHas('applications', function ($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        })->get();

        $csvFileName = 'Итоги_секции' . $sectionId . '.csv';
        $csvData = $this->setProperties($users);

        $file = fopen('php://temp', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        foreach ($csvData as $row) {
            fputcsv($file, $row, ';');
        }
        rewind($file);
        $csvContent = stream_get_contents($file);
        fclose($file);

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $csvFileName . '"');
    }

    public function exportConferenceResults()
    {
        $users = User::whereHas('applications')->get();

        $csvFileName = 'Итоги_конференции.csv';
        $csvData = $this->setProperties($users);

        $csvContent = chr(0xEF).chr(0xBB).chr(0xBF);

        foreach ($csvData as $row) {
            $csvContent .= implode(';', $row) . "\r\n";
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        return response($csvContent, 200, $headers);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_User_C $users
     * @return array
     */
    public function setProperties(\Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_User_C $users): array
    {
        $csvHeaders = ['ФИО', 'Email', 'Номер телефона', 'Дата рождения', 'Город', 'Место обучения', 'Ученая степень'];
        $csvData = [];

        $csvData[] = $csvHeaders;

        foreach ($users as $user) {
            $csvData[] = [
                $user->name,
                $user->email,
                $user->phone_number,
                $user->birthday,
                $user->city,
                $user->study_place,
                $user->education_level->title,
            ];
        }
        return $csvData;
    }
}
