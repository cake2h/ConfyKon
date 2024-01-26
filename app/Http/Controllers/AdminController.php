<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;

class AdminController extends Controller
{
    public function index() 
    {
        $conferences = Conf::all();
        return view('admin.admin', compact('conferences'));
    }
}
