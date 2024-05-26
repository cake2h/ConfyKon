<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Str;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('dashboard.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('conf.index');
    }

    public function yandex()
    {
        return Socialite::driver('yandex')->redirect();
    }

    public function yandexRedirect()
    {
        $user = Socialite::driver('yandex')->user();

        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->user['display_name'],
            'password' => Hash::make(Str::random(24)),
        ]);

        session(['url.intended' => route('register.yandex.page')]);

        Auth::login($user, true);

        return redirect()->intended(route('register.yandex.page'));
    }
}

