<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conf;
use App\Models\Application;

class ConfController extends Controller
{
    public function index()
    {
        $conferences = Conf::all();
        return view('main.index', compact('conferences'));
    }

    public function show(Conf $conference)
    {
        $sections = $conference->sections;
        return view('main.show', compact('conference', 'sections'));
    }

    public function destroy($id)
    {
        Conf::destroy($id);
        return redirect()->route('admin.index');
    }

    public function edit($id)
    {
        $conf = Conf::find($id);
        return view('admin.edit_conference', compact('conf'));
    }

    public function update(Request $request, $id) 
    {
        $conf = Conf::find($id);
        
        $conf->name = $request->input('name');
        $conf->country = $request->input('country');
        $conf->city = $request->input('city');
        $conf->date_start = $request->input('date_start');
        $conf->date_end = $request->input('date_end');
        $conf->deadline = $request->input('deadline');
        $conf->description = $request->input('description');
        $conf->update();

        return redirect()->route('admin.index');
    }

    public function add() 
    {
        return view('admin.add_conference');
    }

    public function store(Request $request)
    {
        $conf = new Conf();

        $conf->name = $request->input('name');
        $conf->country = $request->input('country');
        $conf->city = $request->input('city');
        $conf->date_start = $request->input('date_start');
        $conf->date_end = $request->input('date_end');
        $conf->deadline = $request->input('deadline');
        $conf->description = $request->input('description');
        $conf->save();

        return redirect()->route('admin.index');
    }

    public function application()
    {
        return view('main.subscribe');
    }

    public function subscribe(Request $request, Conf $conference)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'file' => ['required'], // Чек на то что файл, возможно только docx
            'section_id' => ['required'] // Нужно Добавить чек на наличие в таблице
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            
            $file->move(public_path('publications'), $fileName);

            Application::create([
                'name' => $request->name,
                'file_path' => 'publications/' . $fileName,
                'status' => 0,
                'user_id' => Auth::user()->id,
                'section_id' => $request->section_id,
                'type_id' => 1
            ]);
        }
        

        return redirect()->route('dashboard.index');
    }

    public function getSections(Conf $conference)
    {
        $sections = $conference->sections()->select('id', 'name')->get();
        return response()->json($sections);
    }
}
