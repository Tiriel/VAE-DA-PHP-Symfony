<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 11:57
 */

namespace Lib\Exceptions;

use Exception;
use Throwable;

class InternalErrorException extends Exception
{
    public function __construct($message = "Internal Error", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}