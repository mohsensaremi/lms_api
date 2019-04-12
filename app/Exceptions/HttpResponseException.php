<?php
/**
 * Created by PhpStorm.
 * User: mohsen
 * Date: 4/11/19
 * Time: 7:45 PM
 */

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException as BaseClass;
use Illuminate\Http\Response;

class HttpResponseException extends BaseClass
{
    public function __construct($messages, $status)
    {
        $response = new Response(json_encode([
            'messages' => $messages,
            'status' => $status,
        ]));
        parent::__construct($response);
    }
}