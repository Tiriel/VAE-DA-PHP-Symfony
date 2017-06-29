<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 29/06/17
 * Time: 15:40
 */

namespace Lib\Exceptions;

class HttpBadRequestException extends \Exception
{
    public function __construct($message = "Bad Request", $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}