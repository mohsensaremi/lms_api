<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Hash;

class ExampleController extends Controller
{

    public function debug()
    {
       dd(UserToken::all());
    }

    //
}
