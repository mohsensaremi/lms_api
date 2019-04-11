<?php

namespace App\Http\Controllers;

use App\Exceptions\HttpResponseException;
use App\Models\User;
use App\Util\HttpResponse;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    protected function jwt(User $user)
    {
        $now = time();
        $token = $user->tokens()->create([
            'iat' => $now,
        ]);
        $payload = [
            'id' => $token->id,
            'iss' => url(), // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => $now, // Time when JWT was issued.
            'exp' => $now + 60 * 60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * @api {post} /login
     * @apiParam {String} email
     * @apiParam {String} password
     * @apiSuccess {String} accessToken
     */
    public function login()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            throw new HttpResponseException([
                'نام کاربری یا کلمه عبور اشتباه است'
            ], 402);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return new HttpResponse([
                'accessToken' => $this->jwt($user)
            ]);
        }

        throw new HttpResponseException([
            'نام کاربری یا کلمه عبور اشتباه است'
        ], 402);
    }

    /**
     * @api {post} /register
     * @apiParam {String} email
     * @apiParam {String} password
     * @apiParam {String} passwordConfirmation
     * @apiSuccess {String} accessToken
     */
    public function register()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required',
            'passwordConfirmation' => 'required',
        ]);

        if ($this->request->input('password') !== $this->request->input('passwordConfirmation')) {
            throw new HttpResponseException([
                'تایید کلمه عبور اشتباه است'
            ], 402);
        }

        $user = User::where('email', $this->request->input('email'))->first();
        if ($user) {
            throw new HttpResponseException([
                'نام کاربری تکراری است'
            ], 402);
        }

        $user = new User([
            'email' => $this->request->input('email'),
            'password' => Hash::make($this->request->input('password')),
        ]);
        $user->save();
        return new HttpResponse([
            'accessToken' => $this->jwt($user)
        ]);
    }
}