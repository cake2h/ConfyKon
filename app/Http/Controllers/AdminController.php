<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() 
    {
        $conferences = Conf::where('user_id', Auth::id())->get();
        return view('admin.admin', compact('conferences'));
    }
}
