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
use App\Models\Conf;
use App\Models\KonfUser;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = User::find(auth()->id());
        $conferences = [];

        return view('dashboard', compact('user', 'conferences'));
    }
}
