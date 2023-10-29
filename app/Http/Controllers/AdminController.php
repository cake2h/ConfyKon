<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conf;

class AdminController extends Controller
{
    public function index() 
    {
        $confs = Conf::all();
        return view('admin', compact('confs'));
    }
}
