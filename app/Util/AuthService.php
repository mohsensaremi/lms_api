<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/12/19
 * Time: 10:22 PM
 */

namespace App\Util;

use App\Models\User;
use App\Models\UserToken;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class AuthService
{
    protected $request;
    protected $refreshed;
    protected $user;
    protected $payloadUser;
    protected $payload;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->refreshed = false;
        $this->user = null;
        $this->payload = null;
        $this->payloadUser = null;
    }

    public function getRawToken()
    {
        $jwt = $this->request->header('Authorization');
        if (is_null($jwt)) {
            throw new AuthenticationException();
        }
        return trim(str_replace('Bearer', '', $jwt));
    }

    public function verifyToken($jwt)
    {
        try {
            $this->payload = JWT::decode($jwt, env('JWT_SECRET'), ['HS256']);
            $this->payloadUser = new User();
            $this->payloadUser->id = $this->payload->sub;
            $this->payloadUser->type = $this->payload->type;
        } catch (ExpiredException $e) {
            $tks = explode('.', $jwt);
            $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[1]));
            if (!isset($payload->id)) {
                throw new \UnexpectedValueException('id not found in payload');
            }
            if (!isset($payload->type)) {
                throw new \UnexpectedValueException('type not found in payload');
            }
            $token = UserToken::find($payload->id);
            if (!$token || !$token->isValid()) {
                throw new ExpiredException('Expired token');
            }
            $token->load('user');
            $newJwt = $token->refresh();
            $this->refreshed = true;
            $this->verifyToken($newJwt);
        } catch (\Exception $e) {
            throw new AuthenticationException('Not authenticated');
        }
    }

    public function getPayloadUser()
    {
        return $this->payloadUser;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getRefreshed()
    {
        return $this->refreshed;
    }

    public function getUser()
    {
        if (is_null($this->user)) {
            $this->user = User::find($this->payloadUser->id);
        }
        return $this->user;
    }

    public function logout()
    {
        $token = $this->payloadUser->tokens()->find($this->payload->id);
        if ($token) {
            $token->delete();
        }
    }
}