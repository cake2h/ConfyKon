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
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = User::find(auth()->id());
        $conferences = [];

        return view('dashboard', compact('user', 'conferences'));
    }

    public function exportUsers()
    {
        $users = DB::table('users')
            ->select('name', 'surname', 'midname', 'birthday', 'email', 'phone_number', 'city', 'study_place')
            ->where('role', 'user')
            ->get();

        $csvFileName = 'users.csv';
        $csvHeaders = ['Имя', 'Фамилия', 'Отчество', 'Дата рождения', 'Email', 'Номер телефона', 'Город', 'Место обучения'];

        $callback = function() use ($users, $csvHeaders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $csvHeaders, ';'); // Используем точку с запятой в качестве разделителя

            foreach ($users as $user) {
                $userData = get_object_vars($user);
                array_walk($userData, function(&$value) {
                    $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                });
                fputcsv($file, $userData, ';'); // Используем точку с запятой в качестве разделителя
            }

            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ]);
    }
}
