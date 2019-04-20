<?php

namespace App\Models;

use App\Util\WithImagesColumn;

class Course extends Base
{
    use WithImagesColumn;

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
        'image',
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
