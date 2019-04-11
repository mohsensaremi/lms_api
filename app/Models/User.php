<?php

namespace App\Models;


class User extends Base
{

    protected $fillable = [
        'firstName', 'lastName', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens()
    {
        return $this->hasMany(UserToken::class, 'sub', 'id');
    }
}
