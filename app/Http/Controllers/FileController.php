<?php

namespace App\Http\Controllers;

use App\Util\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class FileController extends Controller
{
    /**
     * @param Request $request
     * @return HttpResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadTemp(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $name = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();

        Storage::disk('local')->putFileAs("tmp", $file, $name);

        return new HttpResponse([
            'name' => $name,
        ]);
    }
}
