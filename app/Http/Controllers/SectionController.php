<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;
use App\Models\Section;

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
            'description' => ['required', 'string']
        ]);

        
        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'konf_id' => $conference->id,
            'moder_id' => 1, // изменить под её запрос
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
            'moder_id' => 1, // изменить под её запрос
        ]);

        return redirect()->route('admin.sections.index', $conference);
    }

    public function destroy(Conf $conference, Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index', $conference);
    }
}
