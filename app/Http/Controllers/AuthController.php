<?php

namespace App\Http\Controllers;

use App\Exceptions\HttpResponseException;
use App\Models\User;
use App\Util\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @api {post} /login login
     * @apiGroup Auth
     * @apiParam {String} email
     * @apiParam {String} password
     * @apiSuccess {String} accessToken store it for auth required requests
     */

    /**
     * @return HttpResponse
     * @throws \Illuminate\Validation\ValidationException
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
            ], 422);
        }

        if (Hash::check($this->request->input('password'), $user->password)) {
            return new HttpResponse([
                'accessToken' => $user->generateToken()
            ]);
        }

        throw new HttpResponseException([
            'نام کاربری یا کلمه عبور اشتباه است'
        ], 422);
    }

    /**
     * @api {post} /register register
     * @apiGroup Auth
     * @apiParam {String} email
     * @apiParam {String} password
     * @apiParam {String} passwordConfirmation
     * @apiParam {String} firstName
     * @apiParam {String} lastName
     * @apiParam {String=instructor,student} type user type
     * @apiSuccess {String} accessToken store it for auth required requests
     */

    /**
     * @return HttpResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required',
            'passwordConfirmation' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'type' => 'required|in:' . User::TYPE_INSTRUCTOR . ',' . User::TYPE_STUDENT,
        ]);

        if ($this->request->input('password') !== $this->request->input('passwordConfirmation')) {
            throw new HttpResponseException([
                'تایید کلمه عبور اشتباه است'
            ], 422);
        }

        $user = User::where('email', $this->request->input('email'))->first();
        if ($user) {
            throw new HttpResponseException([
                'نام کاربری تکراری است'
            ], 422);
        }

        $user = new User([
            'email' => $this->request->input('email'),
            'firstName' => $this->request->input('firstName'),
            'lastName' => $this->request->input('lastName'),
            'type' => $this->request->input('type'),
            'password' => Hash::make($this->request->input('password')),
        ]);
        $user->save();
        return new HttpResponse([
            'accessToken' => $user->generateToken()
        ]);
    }

    public function me()
    {
        $user = $this->request->authService->getUser();

        return new HttpResponse([
            'user' => $user,
        ]);
    }

    /**
     * @api {post} /logout logout
     * @apiGroup Auth
     * @apiUse AccessToken
     */

    /**
     * @return HttpResponse
     */
    public function logout()
    {
        $this->request->authService->logout();

        return new HttpResponse();
    }
}