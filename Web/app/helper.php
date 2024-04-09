<?php


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\{Task, User, Sponsor};
use App\Models\Event\{EventUserTicket, UserJoinEvent};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

// Task
if (!function_exists('genCodeTask')) {
    function genCodeTask() {
        $hash = randStrHash(20);
        if (existCodeTask($hash)) {
            return genCodeTask();
        }
        return $hash;
    }
}

if (!function_exists('existCodeTask')) {
    function existCodeTask($hash) {
        return Task::whereCode($hash)->exists();
    }
}
// EndTask

if (!function_exists('eventInfo')) {
    function eventInfo($userId, $taskId) {
        return EventUserTicket::whereUserId($userId)
            ->whereTaskId($taskId)->first();
    }
}

if (!function_exists('totalUserJob')) {
    function totalUserJob($detailId) {
        return UserJoinEvent::whereTaskEventDetailId($detailId)->count();
    }
}

if (!function_exists('codeCardNumber')) {
    function codeCardNumber() {
        $number = mt_rand(100000000000, 999999999999);

        if (checkExists($number)) {
            return codeCardNumber();
        }

        return $number;
    }
}

if (!function_exists('randColor')) {
    function randColor() {
        return '#'.substr(md5(rand()), 0, 6);
    }
}

if (!function_exists('qrNumberCode')) {
    // Gen mã code cho mỗi user (uniq)
    function qrNumberCode () {
        $hash = randStrHash(8);
        if (checkExists($hash, 'cardQr')) {
            return qrNumberCode();
        }

        return $hash;
    }
}

if (!function_exists('codePrize')) {
    function codePrize($code) {
        $code = (int)$code;

        if ($code == 0) {
            return 'Prize 1';
        } elseif ($code == 1) {
            return 'Prize 1';
        } elseif ($code == 2) {
            return 'Prize 3';
        } elseif ($code == 3) {
            return 'Prize 4';
        } else {
            return 'Prize 5';
        }
    }
}

if (!function_exists('affiliateCode')) {
    // Gen mã giới thiệu
    function affiliateCode () {
        $hash = randStrHash(6);
        if (checkExists($hash, 'affiliate')) {
            return affiliateCode();
        }

        return $hash;
    }
}

if (!function_exists('checkExists')) {
    function checkExists($hash, $type = 'cardCode') {
        switch($type) {
            case 'cardQr':
                $checkExists = User::whereQrCode($hash)->exists();
                break;
            case 'affiliate';
                $checkExists = User::whereAffiliateCode($hash)->exists();
                break;
            default:
                $checkExists = CardData::whereHashCode($hash)->exists();
                break;
        }

        return $checkExists;
    }
}

if (!function_exists('randStrHash')) {
    function randStrHash($num) {
        $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJLMNOPRSTUVWXYZ';
        return substr(str_shuffle($string), 0, $num);
    }
}

if (!function_exists('commonImg')) {
    function commonImg($img) {
        if (is_null($img) || $img == '') {
            return null;
        }

        if ($img && Str::contains($img, 'http')) {
            return $img;
        }

        return Storage::disk('s3')->url($img);
    }
}

if (!function_exists('imgAvatar')) {
    function imgAvatar($img) {
        if (is_null($img) || $img == '') {
            return ENV('AWS_URL').'/avatar/avatar.jpg';
        }

        if ($img && Str::contains($img, 'http')) {
            return $img;
        }

        return Storage::disk('s3')->url($img);
    }
}


if (!function_exists('eventImprogess')) {
    function eventImprogess() {
        if (Auth::guest()) {
            return null;
        }

        $userId = Auth::user()->id;
        $data = EventUserTicket::whereUserId($userId)
            ->where('created_at', '<=', Carbon::now()->addDays(3))
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return $data;
    }
}

if (!function_exists('lang')) {
    function lang() {
        return app()->getLocale() ?? 'vi';
    }
}

/*
 * Convert data size to bytes
 * Example 2M => 2097152
 * */
function dataConvertToBytes(bool|string $upload_max_size)
{
    $upload_max_size = trim($upload_max_size);
    $last = strtolower($upload_max_size[strlen($upload_max_size) - 1]);
    $number = substr($upload_max_size, 0, -1);
    switch ($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $number *= 1024;
        // no break
        case 'm':
            $number *= 1024;
        // no break
        case 'k':
            $number *= 1024;
    }

    return $number;
}

if (!function_exists('dateFormat')) {
    function dateFormat($dateTime) {
        if (is_null($dateTime) || $dateTime == '') {
            return null;
        }

        return Carbon::parse($dateTime)->format('Y-m-d H:i');
    }
}

if (!function_exists('isSponsor')) {
    function isSponsor($eventID) {
        if (is_null($eventID) || $eventID == '') {
            return false;
        }

        return Sponsor::whereTaskId($eventID)->exists();
    }
}
