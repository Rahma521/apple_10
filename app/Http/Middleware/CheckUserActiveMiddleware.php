<?php

namespace App\Http\Middleware;

use App\Enums\Options\StatusEnum;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class CheckUserActiveMiddleware
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();

        if ($user) {
                return $next($request);
//            $response = $next($request);
//            $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
//            $response->header('Pragma', 'no-cache');
//            $response->header('Expires', '0');
         }

        return self::errorResponse(__('application.unauthorized'), 403);
    }
}
