<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'original_url', 'shortener_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //shortenUrl
    //Save url by code key
    public static function shortenUrl($url, $length = 1)
    {

        //Check url exist
        $urlCheck = Url::query()
            ->where('original_url', $url)
            ->first();
        if ($urlCheck) {
            return route('shortener-url', ['shortener_url' => $urlCheck->shortener_url]);
        }

        $codeShort = self::getCodeShortener2($length);

        //Check code exist
        $codeCheck = Url::query()
            ->where('shortener_url', $codeShort)
            ->first();

        if ($codeCheck) {
            return route('shortener-url', ['shortener_url' => $codeCheck->shortener_url]);
        } else {
            $codeShort = self::getCodeShortener($length);

            //Check code again
            $codeCheck2 = Url::query()
                ->where('shortener_url', $codeShort)
                ->first();
            if ($codeCheck2) {
                return route('shortener-url', ['shortener_url' => $codeCheck2->shortener_url]);
            } else {
                $codeShort = self::getCodeShortener($length + 1);
            }
        }


        $data['user_id'] = Auth::user()->id;
        $data['title'] = 'Code: ' . $codeShort; // 'Code: 12345
        $data['original_url'] = $url;

        $data['shortener_url'] = $codeShort;

        $model = Url::create($data);

        return route('shortener-url', ['shortener_url' => $data['shortener_url']]);
    }

    //Function get code shortener, return code has not in database
    public static function getCodeShortener2($length = 1){
        //Get total url in db, return count +1
        $totalUrl = Url::query()->count();

        return $totalUrl + 1;
    }
    public static function getCodeShortener($length = 1)
    {
        $codeShort = Str::random($length);

        //Check code exist
        $codeCheck = Url::query()
            ->where('shortener_url', $codeShort)
            ->first();

        if ($codeCheck == false) {
            return $codeShort;
        } else {
            $codeShort = Str::random($length);

            //Check code again
            $codeCheck2 = Url::query()
                ->where('shortener_url', $codeShort)
                ->first();

            if ($codeCheck2 == false) {
                return $codeShort;
            } else {
                $codeShort = Str::random($length + 4);
            }
        }

        return $codeShort;
    }

}
