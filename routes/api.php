<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ConfResource;
use App\Models\Conference;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/conf/{id}', function ($id) {
    return new ConfResource(Conference::findOrFail($id));
});

Route::get('/report/{report}/comments', function (App\Models\Report $report) {
    return $report->reportComments()->orderBy('created_at', 'desc')->get();
});
