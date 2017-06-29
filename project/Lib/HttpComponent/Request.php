<?php

namespace Lib\HttpComponent;

use Lib\Exceptions\InternalErrorException;
use Lib\Exceptions\MethodUnknownException;


/**
 * Class Request
 * @package Lib
 */
class Request
{
    /**
     * @var ParameterBag
     */
    public $query;

    /**
     * @var ParameterBag
     */
    public $body;

    /**
     * @var ParameterBag
     */
    public $cookies;

    /**
     * @var ServerBag
     */
    public $server;

    /**
     * Request constructor.
     * @param $url
     * @throws InternalErrorException
     * @throws MethodUnknownException
     */
    public function __construct($url = null)
    {
        $this->initFromGlobals();
    }

    /**
     * Initializes Query and Body attributes from PHP global vars
     */
    public function initFromGlobals($url = null)
    {
        $decomposed = [
            ''
        ];
        if (null !== $url) {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new InternalErrorException("URL \"$url\" is not recognized as a valid URL.");
            }

            $decomposed = parse_url($url);
        }

        foreach ($decomposed as $part) {
            if ('query' === $part) continue;

            $method    = 'set'.ucfirst($part);

            if (!method_exists($this, $method) && ('user' || 'pass' || 'fragment') !== $part) {
                throw new MethodUnknownException($method, self::class);
            }

            $this->$method($decomposed[$part]);
        }

        $this->query   = new ParameterBag($_GET);
        $this->body    = new ParameterBag($_POST);
        $this->cookies = new ParameterBag($_COOKIE);
        $this->server  = new ServerBag($_SERVER);
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->server->getPath();
    }

    public function getMethod()
    {
        return $this->server->getMethod();
    }
}
