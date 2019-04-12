<?php

namespace App\Models;

use Firebase\JWT\JWT;

class UserToken extends Base
{

    protected $fillable = [
        'iat',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'sub', 'id');
    }

    public function generate()
    {
        $now = time();
        $this->iat = $now;
        $this->save();
        $payload = [
            'id' => $this->id,
            'iss' => url(), // Issuer of the token
            'sub' => $this->user->id, // Subject of the token
            'type' => $this->user->type,
            'iat' => $now, // Time when JWT was issued.
            'exp' => $now + 60 * 60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function isValid()
    {
        return true;
    }

    public function refresh()
    {
        $token = new self();
        $token->user()->associate($this->user);
        $jwt = $token->generate();
        $this->delete();

        return $jwt;
    }
}
