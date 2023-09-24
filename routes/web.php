<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProfileController::class, 'glav'])->name('welcome');

Route::get('/addkonf', function () {
    return view('addkonf');
})->name('addkonf');


Route::get('/dashboard', function () {
    $userRole = auth()->user()->role;
    if ($userRole === 'admin') {
        return view('admin');
    } elseif ($userRole === 'user') {
        return view('dashboard');
    }
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard', [ProfileController::class, 'index'])->name('konf.index');
Route::post('/addkonff', [ProfileController::class, 'store'])->name('konf.store');
Route::post('/regkonf/{id}', [ProfileController::class, 'reg'])->name('konf.reg');

Route::middleware('auth')->group(function () {
    Route::get('/lk', [ProfileController::class, 'edit'])->name('lk');
   
});
Route::get('/delete/{id}', [ProfileController::class, 'delete'])->name('delete')->middleware(['auth']);
Route::get('/konf/{id}', [ProfileController::class, 'updatekonf'])->name('updatekonf')->middleware(['auth']);
Route::post('/up/{id}', [ProfileController::class, 'upkon'])->name('upkon')->middleware(['auth']);
Route::get('/subscribe/{id}', [ProfileController::class, 'subscribe'])->name('subscribe');
require __DIR__.'/auth.php';
