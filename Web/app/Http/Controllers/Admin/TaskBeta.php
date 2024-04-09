<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BaseImage;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskBeta extends Controller
{

    public function __construct()
    {
        $this->middleware('client_admin');
    }

    public function index(Request $request)
    {

        return view(
            'admin.task_beta.index'
        );
    }
    public function edit($id)
    {
        return view(
            'admin.task_beta.edit', [
                'id' => $id
            ]
        );
    }
    public function create(Request $request)
    {
        return view(
            'admin.task_beta.create'
        );
    }
    public function uploadAvatar(Request $request)
    {
        if ($request->hasFile('file')) {
            $avatarFile = $request->file('file');
            $path = 'task/image/banner' . Carbon::now()->format('Ymd');
            $files = Storage::disk('s3')->putFileAs($path, $avatarFile, $avatarFile->hashName());
            return response()->json(BaseImage::imgGroup($files));
        }
        return response()->json();
    }
    public function uploadSliders(Request $request)
    {
        if ($request->hasFile('file')) {
            $avatarFile = $request->file('file');
                $path = 'task/image/banner' . Carbon::now()->format('Ymd');
                $files = Storage::disk('s3')->putFileAs($path, $avatarFile, $avatarFile->hashName());
                $link = BaseImage::imgGroup($files);
            return response()->json($link);
        }
        return response()->json();
    }
}
