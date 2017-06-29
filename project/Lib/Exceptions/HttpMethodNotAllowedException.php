<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 29/06/17
 * Time: 15:44
 */

namespace Lib\Exceptions;


/**
 * Class HttpMethodNotAllowedException
 * @package Lib\Exceptions
 */
class HttpMethodNotAllowedException extends \Exception
{
    /**
     * HttpMethodNotAllowedException constructor.
     * @param array $allowedMethods
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $allowedMethods, $message = "Method Not Allowed", $code = 405, \Throwable $previous = null)
    {
        $asString = implode(', ', $allowedMethods);
        $message .= " - Allowed methods are: {$asString}";

        parent::__construct($message, $code, $previous);
    }
}