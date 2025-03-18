<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $request->header('Accept-Language') == 'ar' ? app()->setLocale('ar') : app()->setLocale('en');
        $user = $request->user('sanctum');
        $user?->lang ?: $user?->update(['lang' => app()->getLocale()]);

        $response = $next($request);
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');

        return $response;
        //return $next($request);
    }

}
