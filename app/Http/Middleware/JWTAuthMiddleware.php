<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserToken;
use Closure;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Firebase\JWT\JWT;

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
        $jwt = $request->header('Authorization');
        if (is_null($jwt)) {
            throw new AuthenticationException();
        }
        $jwt = trim(str_replace('Bearer', '', $jwt));

        $data = self::getUserFromJwt($jwt);
        $user = $data['user'];
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $response = $next($request);

        if ($data['refreshed']) {
            $response->header('Authorization', $data['jwt']);
        }

        return $response;
    }

    public static function getUserFromJwt($jwt, $refreshed = false)
    {
        try {
            $payload = JWT::decode($jwt, env('JWT_SECRET'), ['HS256']);
            $user = new User();
            $user->id = $payload->sub;
            $user->setUserLoaded(false);
            return [
                'user' => $user,
                'jwt' => $jwt,
                'refreshed' => $refreshed,
            ];
        } catch (ExpiredException $e) {
            $tks = explode('.', $jwt);
            $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[1]));
            if (!isset($payload->id)) {
                throw new \UnexpectedValueException('id not found in payload');
            }
            $token = UserToken::find($payload->id);
            if (!$token || !$token->isValid()) {
                throw new ExpiredException('Expired token');
            }
            $token->load('user');
            $newJwt = $token->refresh();
            return self::getUserFromJwt($newJwt, true);
        } catch (\Exception $e) {
            throw new AuthenticationException('Not authenticated');
        }
    }
}