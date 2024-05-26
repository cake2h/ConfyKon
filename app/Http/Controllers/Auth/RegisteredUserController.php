<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $educationLevels = EducationLevel::all();

        return view('auth.register', compact('educationLevels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:25', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'edu_id' => $request->edu_id,
            'study_place' => $request->study_place,
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect('/');
    }

    public function createYandex()
    {
        $userId = auth()->user()->id;
        $educationLevels = EducationLevel::all();

        if(auth()->user()->city == null) {
            return view('auth.yandexLogin', compact('educationLevels', 'userId'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function storeYandex(Request $request, $id)
    {
        $user = User::find($id);

        $user->phone_number = $request->input('phone_number');
        $user->city = $request->input('city');
        $user->edu_id = $request->input('edu_id');
        $user->study_place = $request->input('study_place');
        $user->birthday = $request->input('birthday');
        $user->update();

        return redirect()->route('dashboard.index');
    }
}
