<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Util\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CourseController extends Controller
{
    /**
     * @param Request $request
     * @return HttpResponse
     * @throws \Exception
     */
    public function list(Request $request)
    {
        $user = $request->authService->getPayloadUser();
        $query = null;
        if ($user->type === User::TYPE_INSTRUCTOR) {
            $query = $user->courses()->latest();
        } else if ($user->type === User::TYPE_INSTRUCTOR) {
            throw new \Exception('not implemented');
        } else {
            throw new \Exception('user type invalid');
        }

        $data = $query->paginateList($request->limit, $request->skip);

        return new HttpResponse($data);
    }

    /**
     * @param Request $request
     * @return HttpResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function submit(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $user = $request->authService->getPayloadUser();
        if ($user->type !== User::TYPE_INSTRUCTOR) {
            throw new \Exception('only instructor can submit course');
        }

        if ($request->filled('id')) {
            $model = $user->courses()->findOrFail($request->get('id'));
        } else {
            $model = new Course();
            $model->user()->associate($user);
        }

        $model->fill(
            $request->only('title', 'description')
        );
        $model->uploadInputFiles($request->get('images'));

        if (!$request->filled('id')) {
            $this->validate($request, [
                'password' => 'required_if:hasPassword,true',
            ]);
        }

        if ($request->get('hasPassword') && $request->filled('password')) {
            $model->password = Hash::make($request->get('password'));
        }

        $model->save();

        return new HttpResponse();
    }
}
