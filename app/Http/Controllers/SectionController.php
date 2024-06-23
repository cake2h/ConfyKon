<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

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
            'moderator_email' => ['required', 'email'],
            'event_date' => ['required', 'date'],
            'event_place' => ['required', 'string'],
        ]);

        $moderator = User::where('email', $request->moderator_email)->first();

        if (!$moderator) {
            return redirect()->back()->withInput()->withErrors(['moderator_email' => 'Модератор с указанным email не найден.']);
        }

        if ($moderator->role !== 'moderator') {
            $moderator->role = 'moderator';
            $moderator->save();
        }

        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'konf_id' => $conference->id,
            'moder_id' => $moderator->id,
            'event_date' => $request->event_date,
            'event_place' => $request->event_place,
        ]);

        return redirect()->route('admin.sections.index', $conference);
    }

    public function edit(Conf $conference, Section $section)
    {
        $moderatorEmail = $section->moder->email;
        return view('admin.sections.edit', compact('conference', 'section', 'moderatorEmail'));
    }

    public function update(Request $request, Conf $conference, Section $section)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'moderator_email' => ['required', 'email'],
            'event_date' => ['required', 'date'],
            'event_place' => ['required', 'string'],
        ]);

        $newModerator = User::where('email', $request->moderator_email)->first();

        if (!$newModerator) {
            return Redirect::back()->withErrors(['moderator_email' => 'Пользователь с указанным email не найден.']);
        }

        if ($section->moder_id && $section->moder_id !== $newModerator->id) {
            $currentModerator = User::find($section->moder_id);
            if ($currentModerator && $currentModerator->role === 'moderator') {
                $currentModerator->role = 'user';
                $currentModerator->save();
            }
        }

        if ($newModerator->role !== 'moderator') {
            $newModerator->role = 'moderator';
            $newModerator->save();
        }

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
            'moder_id' => $newModerator->id,
            'event_date' => $request->event_date,
            'event_place' => $request->event_place,
        ]);

        return redirect()->route('admin.sections.index', $conference)->with('success', 'Секция успешно обновлена.');
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
