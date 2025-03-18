<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetOrganization
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('sanctum')->check()) {
            // Get organization_id from authenticated user
            $organizationId = auth('sanctum')->user()->organization_id;
            // Share with all views
            view()->share('organization_id', $organizationId);

            // Add to request for controller access
            $request->merge(['organization_id' => $organizationId]);
        }
        return $next($request);
    }
}
