<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;

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

    public function subscribe(Request $request)
    {
        $request->validate([
            
        ]);
        
        return redirect()->route('mains.index');
    }

    public function getSections(Conf $conference)
    {
        $sections = $conference->sections()->select('id', 'name')->get();
        return response()->json($sections);
    }
}
