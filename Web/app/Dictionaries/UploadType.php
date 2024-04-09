<?php
/**
 * Created by PhpStorm.
 * User: dungpx
 * Date: 11/25/2022
 * Time: 10:31 AM
 */

namespace App\Dictionaries;

/*
 * User status
 * */

class UploadType
{
    const LOCAL = 1;
    const CLOUDINARY = 2;
    const S3 = 3;

    public static function all()
    {
        return [
            self::LOCAL => 'Local',
            self::CLOUDINARY => 'Cloudinary',
            self::S3 => 'S3',
        ];
    }

    public static function get($type)
    {
        $all = self::all();

        if (isset($all[$type])) {
            return $all[$type];
        }

        return '';
    }
}
