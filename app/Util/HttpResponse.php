<?php

namespace App\Util;

class HttpResponse
{
    protected $content;

    public function __construct($data)
    {
        $this->content = [
            'data' => $data,
            'status' => 200,
        ];
    }

    public function __toString()
    {
        return json_encode($this->content);
    }
}