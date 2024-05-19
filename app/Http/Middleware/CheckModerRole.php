<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckModerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Проверяем, авторизован ли пользователь и является ли его роль 'moder'
        if (Auth::check() && Auth::user()->role === 'moderator') {
            return $next($request);
        }

        // Если не авторизован или не 'moder', перенаправляем на главную страницу или выводим ошибку
        return redirect('/')->with('error', 'У вас нет доступа к этому разделу');
    }
}
