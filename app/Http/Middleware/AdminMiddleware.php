<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        // If user is logged in AND their role is admin, let them pass
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        // Otherwise, send them to the regular dashboard
        return redirect('/dashboard')->with('error', 'Access Denied.');
    }
}