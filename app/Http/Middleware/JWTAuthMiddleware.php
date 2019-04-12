<?php

namespace App\Http\Middleware;

use Closure;
use App\Util\AuthService;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->authService = new AuthService($request);
        $jwt = $request->authService->getRawToken();
        $request->authService->verifyToken($jwt);

        $response = $next($request);

        if ($request->authService->getRefreshed()) {
            $response->header('Authorization', $jwt);
        }

        return $response;
    }
}