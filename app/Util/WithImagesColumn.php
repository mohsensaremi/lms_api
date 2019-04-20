<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/20/19
 * Time: 8:03 PM
 */

namespace App\Util;


trait WithImagesColumn
{
    public function getImagesAttribute()
    {
        try {
            return collect(json_decode($this->attributes['images'], true))
                ->filter(function ($item) {
                    return is_array($item) && isset($item['name']);
                })
                ->map(function ($item) {
                    return array_merge($item, [
                        'url' => url('file/' . $item['name']),
                    ]);
                })
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getImageAttribute()
    {
        return collect($this->images)->first();
    }
}