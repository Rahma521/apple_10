<?php

namespace App\Http\Middleware;

use App\Enums\Options\StatusEnum;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckAdminActiveMiddleware
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        $admin = auth('admin')->user();
        // Check if the admin is logged in
        if ($admin) {
              //  return $next($request);
            $response = $next($request);
            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', '0');

            return $response;
        }
        return self::errorResponse(__('application.unauthorized'), 403);
    }
}
