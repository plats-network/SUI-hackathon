<?php

namespace App\Exceptions;

use Exception;

class NotDetectionClass extends Exception
{
    protected $message = 'Class is undefined';
}
