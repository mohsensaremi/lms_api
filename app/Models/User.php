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

    protected $appends = [
        'typeFa',
    ];

    public function getTypeFaAttribute()
    {
        switch ($this->type) {
            case self::TYPE_STUDENT:
                return 'دانشجو';
            case self::TYPE_INSTRUCTOR:
                return 'آموزگار';
            case self::TYPE_ADMINISTRATOR:
                return 'مدیر';
            default:
                return '';
        }
    }

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

    public function courses()
    {
        if ($this->type === self::TYPE_INSTRUCTOR) {
            return $this->hasMany(Course::class, 'userId', 'id');
        }
    }
}
