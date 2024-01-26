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
        return view('main.show', compact('conference'));
    }

    public function destroy($id)
    {
        Conf::destroy($id);
        return redirect()->route('admin.index');
    }

    public function edit($id)
    {
        $conf = Conf::find($id);
        return view('conf.edit', compact('conf'));
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
        return view('conf.add');
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
}
