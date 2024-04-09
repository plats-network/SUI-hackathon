<?php

namespace App\Exceptions;

use Exception;

class ImageNotFound extends Exception
{
    protected $message = "Image not found";
}
