<?php

namespace App\Http\Middleware;

use App\Models\Agency;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->agency_id) {
            abort(403, 'Aucune agence associée à ce compte.');
        }

        $agency = Agency::find($user->agency_id);

        if (!$agency) {
            abort(403, 'Agence introuvable.');
        }

        if (!$agency->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre agence a été suspendue. Contactez l\'administrateur.',
            ]);
        }

        app()->instance('current_agency', $agency);

        return $next($request);
    }
}
