<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;

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
        if (Auth::check()) {
            $user = Auth::user();
            if (Section::where('moder_id', $user->id)->exists()) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'У вас нет прав для доступа к панели модератора');
    }
}
