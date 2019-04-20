<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @apiDefine AccessToken
     * @apiHeader (Auth) {String} Authorization Authorization JSON web token.
     * @apiHeaderExample {json} Header-Example:
    { "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJh..." }
     */

    /**
     * @apiDefine Paginate
     * @apiHeader (Paginate) {Number} limit=15 limit value for paginate query.
     * @apiHeader (Paginate) {Number} skip=0 skip value for paginate query.
     * @apiHeaderExample {json} Request-Example:
    { "limit": 10, "skip": 5 }
     */
}
