<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;
use App\Models\Section;
use App\Models\User;

class SectionController extends Controller
{
    public function adminSections(Conf $conference)
    {
        return view('admin.sections.index', compact('conference'));
    }

    public function add(Conf $conference)
    {
        return view('admin.sections.add', compact('conference'));
    }

    public function store(Request $request, Conf $conference)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'moderator_email' => ['required', 'email'], 
        ]);

        $moderator = User::where('email', $request->moderator_email)->first();

        if ($moderator && $moderator->role !== 'moderator') {
            $moderator->role = 'moder';
            $moderator->save();
        }

        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'konf_id' => $conference->id,
            'moder_id' => $moderator ? $moderator->id : null, 
        ]);

        return redirect()->route('admin.sections.index', $conference);
    }

    public function edit(Conf $conference, Section $section)
    {
        return view('admin.sections.edit', compact('conference', 'section'));
    }

    public function update(Request $request, Conf $conference, Section $section)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
            'moder_id' => 1,
        ]);

        return redirect()->route('admin.sections.index', $conference);
    }

    public function destroy(Conf $conference, Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index', $conference);
    }

    public function addModerator(Request $request, $conferenceId)
    {
        $conference = Conf::findOrFail($conferenceId);
        $moderatorEmail = $request->input('moderator_email');

        $moderator = User::where('email', $moderatorEmail)->first();

        if (!$moderator) {
            return redirect()->back()->with('error', 'Пользователь с указанным email не найден.');
        }

        $moderator->role = 'moder';
        $moderator->save();

        return redirect()->back()->with('success', 'Модератор успешно добавлен.');
    }
}