<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAgencyActive
{
    public function handle(Request $request, Closure $next): Response
    {
        // Super admin bypasses SetTenantMiddleware — current_agency is never bound
        if (!app()->bound('current_agency')) {
            return $next($request);
        }

        $agency = app('current_agency');

        if ($agency && !$agency->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre agence a été suspendue.',
            ]);
        }

        return $next($request);
    }
}
