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

class MethodUnknownException extends Exception
{
    public function __construct($method, $class, $message = "", $code = 0, Throwable $previous = null)
    {
        if ("" === $message) {
            $message = "Method $method doesn't exist in class $class";
        }
        parent::__construct($message, $code, $previous);
    }
}