<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() 
    {
        $conferences = Conference::where('user_id', Auth::id())->get();
        return view('admin.admin', compact('conferences'));
    }
}
