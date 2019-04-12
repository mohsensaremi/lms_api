<?php

namespace App\Models;

class Course extends Base
{

    protected $fillable = [
        'title',
        'description',
        'password',
        'images'
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'hasPassword',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function getHasPasswordAttribute()
    {
        return !is_null($this->password);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
