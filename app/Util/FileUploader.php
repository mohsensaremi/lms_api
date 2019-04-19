<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/19/19
 * Time: 4:52 PM
 */

namespace App\Util;

use Illuminate\Support\Facades\Storage;

class FileUploader
{
    protected $input;
    protected $freshInput;
    protected $oldInput;

    public function __construct($input)
    {
        $this->input = collect($input)
            ->filter(function ($item) {
                return isset($item['name']);
            });

        $this->freshInput = $this->input
            ->filter(function ($item) {
                return isset($item['fresh']) && $item['fresh'] === true;
            });

        $this->oldInput = $this->input
            ->filter(function ($item) {
                return !isset($item['fresh']) || $item['fresh'] === false;
            });
    }

    public function getFreshInput()
    {
        return $this->freshInput;
    }

    public function getOldInput()
    {
        return $this->oldInput;
    }

    public function moveFresh()
    {
        $this->freshInput->map(function ($item) {
            Storage::disk('local')->move('tmp/' . $item['name'], 'public/' . $item['name']);
        });

        return $this;
    }

    public function checkOldInput($items)
    {
        $items = collect($items)->filter(function ($item) {
            return isset($item['name']);
        });

        $this->oldInput->map(function ($item) use ($items) {
            $found = $items->where('name', $item['name']);
            if ($found->count() === 0) {
                Storage::disk('local')->delete('public/' . $item['name']);
            }
        });

        return $this;
    }

    public function getFiles()
    {
        return $this->input->map(function ($item) {
            return [
                'name' => $item['name'],
            ];
        });
    }
}