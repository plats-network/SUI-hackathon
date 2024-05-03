<?php

namespace App\Helpers;
// use App\Helpers\BaseImage;

use Illuminate\Support\Str;
use Storage;

class BaseImage {
    public static function loadImage($image_url = null)
    {
        if (is_null($image_url)) {
            
            // return asset('/imgs/imagedefault.jpg');

            return Storage::disk('s3')->url('icon/hidden_box.png');
        }
        if (strpos($image_url ,'https') !== false){
            return $image_url;
        }
        return Storage::disk('s3')->url($image_url);
    }

    public static function pspIcon($image_url = null)
    {
        if (is_null($image_url)) {
            return Storage::disk('s3')->url('icon/psp-icon.png');
        }

        return Storage::disk('s3')->url($image_url);
    }

    public static function getType($type)
    {
        switch($type) {
            case 0:
                $type_label = 'token';
                break;
            case 1:
                $type_label = 'nft';
                break;
            case 2:
                $type_label = 'voucher';
                break;
            default:
                $type_label = 'card';
        }

        return $type_label;
    }

    public static function imgGroup($img)
    {
        if (Str::contains($img, ['http://', 'https://'])) {
            return $img;
        } elseif ($img) {
            return Storage::disk('s3')->url($img);
        }

        return Storage::disk('s3')->url('icon/psp-icon.png');
    }
}
