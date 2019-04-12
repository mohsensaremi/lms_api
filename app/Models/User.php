<?php

namespace App\Models;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class User extends Base
{
    const TYPE_STUDENT = 'student';
    const TYPE_INSTRUCTOR = 'instructor';
    const TYPE_ADMINISTRATOR = 'administrator';

    protected $fillable = [
        'firstName', 'lastName', 'email', 'password', 'type',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens()
    {
        return $this->hasMany(UserToken::class, 'sub', 'id');
    }

    public function generateToken()
    {
        $token = new UserToken();
        $token->user()->associate($this);
        return $token->generate();
    }
}
