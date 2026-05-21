<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRevoke
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $status = Auth::user()->status;

            // If the user is anything other than 'approved', send them to the pending page
            if (in_array($status, ['pending', 'declined', 'revoked'])) {
                
                // Allow them to access the pending page, but nothing else
                if (!$request->routeIs('waiting.approval')) {
                    return redirect()->route('waiting.approval');
                }
            }
        }

        return $next($request);
    }
}