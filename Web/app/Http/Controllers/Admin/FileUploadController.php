<?php

namespace App\Http\Controllers\Admin;

use App\Dictionaries\UploadType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
 * Upload Data to Cloudinary
 * */

class FileUploadController extends Controller
{
    public function uploadFile($file, $uploadType = 3)
    {
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize(); //Example 2708308 bytes
        $type = $file->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename . '_' . str_shuffle(time()) . '.' . $extension;

        /*Full Path*/
        $fullPath = $this->getFullPath();
        $result = $fullPath . '/' . $fileNameToStore;

        switch ($uploadType) {
            case UploadType::CLOUDINARY:
                //public/images/2022/12/80_07_6284168198.jpg
                $result = $file->storeOnCloudinaryAs($fullPath, $fileNameToStore);

                //dd($result->getPublicId().'.'.$result->getExtension());
                $filePath = $result->getPublicId() . '.' . $result->getExtension();
                $result = $filePath;
                break;
            case UploadType::S3:
                //public/images/202302/Picture12_6707615116.png
                $result = $file->storeAs($fullPath, $fileNameToStore, 's3');
                break;
        }

        $fileInfo = [
            'name' => $filename,
            'extension' => $extension,
            'size' => $size,
            'type' => $type,
            'path' => $fullPath . '/' . $fileNameToStore,
            'path_store' => $fileNameToStore,
            'url' => env('AWS_URL', 'https://vndevslar.s3.ap-northeast-1.amazonaws.com') . '/' .
                $fullPath . '/' . $fileNameToStore,
        ];

        // upload file
        $this->doUploadFileStorage($result, $uploadType, $fileInfo);

        return $fileInfo;
    }

    /**
     * @param $result
     * @param $type
     * @param $fileInfo
     * @return array
     */
    private function doUploadFileStorage($result, $type = 3, $fileInfo = [])
    {
        //dd($fileInfo);
        $item = [];
        if ($type == UploadType::CLOUDINARY) {
            $imagePath = $result;
        }
        //$imagePath = $result;
        if ($type == UploadType::S3) {
            $imagePath = $fileInfo['path'] ?? $result;
        }

        $item['name'] = 'Profile.jpg';
        //$item['type'] = $result->getFileType();
        $item['type'] = 'image/png';
        $item['size'] = '';

        //http://localhost/vaixgroup/fujiken/public/storage/public/images/Screenshot_1_1663927184.png
        $appBaseUrl = asset('storage');
        if ($type == 2) {
            //https://res.cloudinary.com/dhploi5y1/image/upload/v1669881623/public/images/2022/12/80_07_8531819668.jpg.jpg
            //https://res.cloudinary.com/dhploi5y1/image/upload/v1669881622/public/images/2022/12/80_02_2851968062.jpg
            $cloudinaryname = config('cloudinary.cloud_app');
            if (empty($cloudinaryname)) {
                $cloudinaryname = 'dhploi5y1';
            }
            $appBaseUrl = "http://res.cloudinary.com/$cloudinaryname/image/upload/";
        }

        if ($type == 3) {
            $appBaseUrl = env('AWS_URL', 'https://vndevslar.s3.ap-northeast-1.amazonaws.com');
            if ($appBaseUrl == '') {
                $appBaseUrl = config('filesystems.disks.s3.url');
            }
        }

        $fullUrl = $appBaseUrl . '/' . $fileInfo['path_store'] ?? $imagePath;
        if ($type == 3) {
            $fullUrl = $appBaseUrl . '/' . $imagePath;
        }

        //http://localhost/vaixgroup/fujiken/public/storage/public/images/Screenshot_1_1663927695.png
        //$fullUrl = Str::replace('public/images', 'images', $fullUrl);

        $item['base_url'] = $appBaseUrl;
        //$item['path'] = Str::replace('public/images', 'images', $imagePath);
        $item['path'] = $appBaseUrl . '/' . $imagePath;
        $item['path_store'] = $fileInfo['path_store'] ?? $imagePath;
        $item['url'] = $fullUrl;
        //$item['url'] = $appBaseUrl . '/'.$imagePath;
        $item['url_full'] = $fullUrl;
        //Tinymce
        $item['location'] = $fullUrl;
        $item['filelink'] = $fullUrl;
        $item['delete_url'] = route('delete-image', ['type' => $type, 'path' => $imagePath]);

        return $item;
    }
}
