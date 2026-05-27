<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->locale
            ?? session('locale')
            ?? config('locales.defaut');

        $supported = config('locales.supported', ['fr', 'en', 'pt-PT']);

        App::setLocale(in_array($locale, $supported) ? $locale : 'fr');

        return $next($request);
    }
}
