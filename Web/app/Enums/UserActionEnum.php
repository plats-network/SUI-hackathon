<?php

namespace App\Enums;

enum UserActionEnum:string
{
    case LIKE = 'like';
    case UNLIKE = 'unlike';
    case PIN = 'pin';
    case UNPIN = 'unpin';
}
