<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Section;
use App\Models\Application;

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
        if ($application) {
            $application->status = 1;
            $application->save();
        }

        return redirect()->back()->with('success', 'Заявка одобрена.');
    }

    public function reject($id)
    {
        $application = Application::find($id);
        if ($application) {
            $application->status = 2;
            $application->save();
        }

        return redirect()->back()->with('success', 'Заявка отклонена.');
    }
}
