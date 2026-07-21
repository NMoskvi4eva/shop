<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
{
    // Якщо користувач не авторизований АБО його email не є адмінським — викидаємо на головну
    if (!auth()->check() || auth()->user()->email !== 'admin@gmail.com') {
        return redirect('/')->with('error', 'У вас немає доступу до адміністративної панелі!');
    }

    return $next($request);
}
}
