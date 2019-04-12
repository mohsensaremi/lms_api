<?php

namespace App\Models;

class User extends Base
{
    protected $userLoaded = true;

    protected $fillable = [
        'firstName', 'lastName', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setUserLoaded($userLoaded)
    {
        $this->userLoaded = $userLoaded;
    }

    public function getUserLoaded()
    {
        return $this->userLoaded;
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

    public function getAttribute($key)
    {
        if ($this->userLoaded || $key === $this->getKeyName()) {
            return parent::getAttribute($key);
        }
        $this->loadUser();
        return $this->getAttribute($key);
    }
    
    public function loadUser(){
        $user = self::find($this->getKey());
        $this->fill($user->getAttributes());
        $this->userLoaded = true;
    }
}
