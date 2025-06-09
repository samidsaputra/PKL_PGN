<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AutoLogout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity', now());

            // Cek apakah sudah lebih dari 30 detik sejak aktivitas terakhir
            if (Carbon::parse($lastActivity)->diffInSeconds(now()) > 30000) {
                Auth::logout();
                session()->flush();
                return redirect('/')->with('error', 'Session expired due to inactivity.');
            }

            // Perbarui waktu aktivitas terakhir di sesi
            session(['last_activity' => now()]);
        }

        return $next($request);
    }
}
