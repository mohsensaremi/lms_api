<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/11/19
 * Time: 7:29 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Base extends Model
{
    public $incrementing = false;
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }
}