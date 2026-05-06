<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is NOT an admin, kick them back to the dashboard
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}