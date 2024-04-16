<?php

namespace App\Models;

use App\Models\Traits\Uuid;

class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken
{
    use Uuid;
}
