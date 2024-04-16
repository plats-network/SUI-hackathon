<?php

namespace App\Exceptions;

use Exception;

class NotDetectionModel extends Exception
{
    protected $message = 'Model is undefined';
}
