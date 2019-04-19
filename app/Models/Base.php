<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/11/19
 * Time: 7:29 PM
 */

namespace App\Models;

use App\Util\FileUploader;
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

    public function scopePaginateList($query, $limit = null, $skip = null)
    {
        $limit = is_null($limit) ? 15 : $limit;
        $skip = is_null($skip) ? 0 : $skip;

        $total = $query->count();
        $list = $query->skip($skip)->limit($limit)->get();
        return [
            'total' => $total,
            'list' => $list,
        ];
    }

    public function uploadInputFiles($input, $column = 'images')
    {
        if (is_array($input)) {
            $uploader = new FileUploader($input);
            $uploader->moveFresh()->checkOldInput($this->{$column});
            $this->{$column} = $uploader->getFiles();
        } else {
            $this->{$column} = [];
        }
        return $this;
    }
}