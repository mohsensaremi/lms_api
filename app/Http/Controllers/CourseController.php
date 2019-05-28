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
     * @api {get} /course/list list
     * @apiDescription return current user course list. if instructor: list of created courses. if student: list of joined courses
     * @apiGroup Course
     * @apiUse Paginate
     * @apiSuccess {Object[]} data
     * @apiSuccess {Number} status
     * @apiSuccessExample {json} Instructor course list:
     * { "status": 200, "data": [{"id":"123", "title":"sample", "description":"sample", "images":[{"name":"sample.jpg", "url":"http://localhost/sample.jpg"}]}] }
     */
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
        } else if ($user->type === User::TYPE_STUDENT) {
            throw new \Exception('not implemented');
        } else {
            throw new \Exception('user type invalid');
        }

        $data = $query->paginateList($request->limit, $request->skip);

        return new HttpResponse($data);
    }

    /**
     * @api {post} /course/submit submit
     * @apiDescription submit a course. if id provided in request update it, otherwise create new record with given data.
     * @apiGroup Course
     * @apiParam {String} title course title.
     * @apiParam {String} [description] course description.
     * @apiParam {Object[]} [images] list of images. each object should contain name. objects returned from `/file/upload-temp` api should send here
     * @apiParam {String} [password] class password for join
     * @apiUse AccessToken
     */

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
