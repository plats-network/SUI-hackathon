<?php

namespace App\Enums;

enum TweetEnum:string
{
    case FOLLOW = 'follow';
    case LIKE = 'like';
    case RETWEET = 'retweet';
    case HASHTAG = 'hashtag';
}
