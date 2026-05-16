<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    public function handle(Request $request, Closure $next): mixed
    {
        // diag 7 : إذا المستخدم معطل — يخرج تلقائياً
        if (Auth::check() && !Auth::user()->isActive()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                             ->withErrors(['email' => 'Votre compte a été désactivé.']);
        }

        return $next($request);
    }
}