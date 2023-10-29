<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ConfResource;
use App\Models\Konf;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/conf/{id}', function ($id) {
    return new ConfResource(Konf::findOrFail($id));
});
