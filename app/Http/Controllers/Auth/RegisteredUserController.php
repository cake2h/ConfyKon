<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\StudyPlace;
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
        $studyPlaces = StudyPlace::all();

        return view('auth.register', compact('educationLevels', 'studyPlaces'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'surname' => ['required', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s]+$/u'],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s]+$/u'],
            'patronymic' => ['nullable', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:25', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'city_id' => ['required', 'exists:cities,id'],
            'education_level_id' => ['required', 'exists:education_levels,id'],
            'study_place_id' => ['required', 'exists:study_places,id'],
            'birthday' => ['required', 'date'],
        ], [
            'surname.regex' => 'Фамилия должна содержать только русские буквы',
            'name.regex' => 'Имя должно содержать только русские буквы',
            'patronymic.regex' => 'Отчество должно содержать только русские буквы',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'patronymic' => $request->patronymic,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
            'phone_number' => $request->phone_number,
            'city_id' => $request->city_id,
            'education_level_id' => $request->education_level_id,
            'study_place_id' => $request->study_place_id,
            'consent_to_mailing' => $request->boolean('consent_to_mailing', false),
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect('/');
    }

    public function createYandex()
    {
        $userId = auth()->user()->id;
        $educationLevels = EducationLevel::all();
        $studyPlaces = StudyPlace::all();

        if(auth()->user()->city_id == null) {
            return view('auth.yandexLogin', compact('educationLevels', 'studyPlaces', 'userId'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }

    public function storeYandex(Request $request, $id)
    {
        $user = User::find($id);

        $user->phone_number = $request->input('phone_number');
        $user->city_id = $request->input('city_id');
        $user->education_level_id = $request->input('education_level_id');
        $user->study_place_id = $request->input('study_place_id');
        $user->birthday = $request->input('birthday');
        $user->update();

        return redirect()->route('dashboard.index');
    }
}
