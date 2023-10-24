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
use App\Models\Konf;
use App\Models\KonfUser;

class ProfileController extends Controller
{
    public function lk(Request $request): View
    {
        $userData = User::find(auth()->id());

        return view('lk', compact('userData'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function index()
    {
        $konfs = Konf::all();

        return view('main',['konfs' => $konfs]);
    }

    public function store(Request $request)
    {
        $konf = new Konf();
        $konf->name = $request->input('name');
        $konf->country = $request->input('country');
        $konf->city = $request->input('city');
        $konf->date_start = $request->input('date_start');
        $konf->date_end = $request->input('date_end');
        $konf->deadline = $request->input('deadline');
        $konf->description = $request->input('description');
        $konf->save();

        return redirect()->route('konf.index');
    }

    public function subscribe($id )
    {
        $master_class = Konf::find($id);
        return view('regkonf', ['data' => $master_class]);
    }

    public function reg($id, Request $request)
    {
        $master_class = Konf::find($id);
        $konf = new KonfUser();
        $konf->konf_id=$id;
        $konf->user_id=Auth::user()->id;
        $konf->name_project=$request->input('name_project');
        $konf->save();

        return redirect('/');
    }

    public function main()
    {
        $konfs = Konf::all();
        return view('main',['konfs' => $konfs]);
    }
    public function delete($id)
    {
        Konf::destroy($id);
        return redirect('/');
    }
    public function updatekonf($id)
    {

        $konfs = Konf::find($id);
        return view('updatekonf',['konfs' => $konfs]);
    }

    public function upkon(Request $request, $id) {

        $konf = Konf::find($id);
        $konf->name = $request->input('name');
        $konf->country = $request->input('country');
        $konf->city = $request->input('city');
        $konf->date_start = $request->input('date_start');
        $konf->date_end = $request->input('date_end');
        $konf->deadline = $request->input('deadline');
        $konf->description = $request->input('description');
        $konf->update();
        return redirect('/');
    }
}
