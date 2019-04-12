<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Util\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CourseController extends Controller
{

    public function list(Request $request)
    {
        $user = $request->authService->getPayloadUser();
        $query = null;
        if ($user->type === User::TYPE_INSTRUCTOR) {
            $query = $user->courses()->latest();
        } else if ($user->type === User::TYPE_INSTRUCTOR) {
            throw new \UnexpectedValueException('not implemented');
        } else {
            throw new \UnexpectedValueException('user type invalid');
        }

        $data = $query->paginateList($request->limit, $request->skip);

        return new HttpResponse($data);
    }
}
