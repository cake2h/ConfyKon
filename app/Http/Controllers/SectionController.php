<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class SectionController extends Controller
{
    public function adminSections(Conference $conference)
    {
        return view('admin.sections.index', compact('conference'));
    }

    public function add(Conference $conference)
    {
        return view('admin.sections.add', compact('conference'));
    }

    public function store(Request $request, Conference $conference)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'moderator_email' => ['required', 'email'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after:date_start'],
            'event_place' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'url', 'max:255'],
        ]);

        $moderator = User::where('email', $request->moderator_email)->first();

        if (!$moderator) {
            return redirect()->back()->withInput()->withErrors(['moderator_email' => 'Модератор с указанным email не найден.']);
        }

        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'conference_id' => $conference->id,
            'user_id' => $moderator->id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'event_place' => $request->event_place,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.index', $conference);
    }

    public function edit(Conference $conference, Section $section)
    {
        $moderatorEmail = $section->moder->email;
        return view('admin.sections.edit', compact('conference', 'section', 'moderatorEmail'));
    }

    public function update(Request $request, Conference $conference, Section $section)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'moderator_email' => ['required', 'email'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date', 'after:date_start'],
            'event_place' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'url', 'max:255'],
        ]);

        $newModerator = User::where('email', $request->moderator_email)->first();

        if (!$newModerator) {
            return Redirect::back()->withErrors(['moderator_email' => 'Пользователь с указанным email не найден.']);
        }

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $newModerator->id,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'event_place' => $request->event_place,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.index', $conference)->with('success', 'Секция успешно обновлена.');
    }

    public function destroy(Conference $conference, Section $section)
    {
        $section->delete();

        return redirect()->route('admin.index', $conference);
    }

    public function addModerator(Request $request, $conferenceId)
    {
        $conference = Conference::findOrFail($conferenceId);
        $moderatorEmail = $request->input('moderator_email');

        $moderator = User::where('email', $moderatorEmail)->first();

        if (!$moderator) {
            return redirect()->back()->with('error', 'Пользователь с указанным email не найден.');
        }

        return redirect()->back()->with('success', 'Модератор успешно добавлен.');
    }
}
