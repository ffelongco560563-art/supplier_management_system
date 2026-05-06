<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckApproval
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If not logged in, ignore this middleware
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 2. LOGIC: If user is an ADMIN, always let them through
        if ($user->role === 'admin') {
            return $next($request);
        }

        // 3. LOGIC: If they are a client and NOT approved
        // Note: We use lowercase 'approved' to match your Tinker command
        if ($user->status !== 'approved') {
            
            // Allow them to see the waiting page, otherwise they get a redirect loop
            if ($request->routeIs('waiting.approval') || $request->is('logout')) {
                return $next($request);
            }

            return redirect()->route('waiting.approval');
        }

        // 4. LOGIC: If they ARE approved but still trying to view the waiting page
        if ($user->status === 'approved' && $request->routeIs('waiting.approval')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}