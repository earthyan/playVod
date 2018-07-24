<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class APIException extends Exception
{
    public function __construct($message = "", $code = 1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}