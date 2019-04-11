<?php

namespace App\Models;


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

}
