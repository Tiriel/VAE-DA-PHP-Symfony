<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 12:30
 */

namespace Lib\HttpComponent;

class ServerBag
{
    /**
     * @var string
     */
    private $_method;
    private $_scheme;
    private $_user;
    private $_pass;
    private $_host;
    private $_port;
    private $_path;
    private $_query;

    /**
     * @var array
     */
    public $dump;

    /**
     * ServerBag constructor.
     * @param $server
     */
    public function __construct($server)
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];
        $this->_scheme = $this->getScheme();
        $this->_user   = null !== $_SERVER['PHP_AUTH_USER'] ? $_SERVER['PHP_AUTH_USER'] : null ;
        $this->_pass   = null !== $_SERVER['PHP_AUTH_PW'] ? $_SERVER['PHP_AUTH_PW'] : null ;
        $this->_host   = $_SERVER['HTTP_HOST'];
        $this->_port   = $this->getPort();
        $this->_path   = $_SERVER['SCRIPT_URL'];
        $this->_query  = $_SERVER['QUERY_STRING'];
        $this->dump    = $_SERVER;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        $scheme = 'http';
        if (false !== $_SERVER['HTTPS'] || 'https' === strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $scheme = 'https';
        }
        return $scheme;
    }

    /**
     * @return null|string
     */
    public function getPort()
    {
        $port = '80' !== $_SERVER['SERVER_PORT'] ? $_SERVER['SERVER_PORT'] : null ;

        $default = [
            null,
            '80',
            $_SERVER['SERVER_PORT']
        ];
        if (!in_array($_SERVER['HTTP_X_FORWARDED_PORT'], $default)) {
            $port = $_SERVER['HTTP_X_FORWARDED_PORT'];
        }

        return $port;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        $auth  = null !== $this->_user ? "{$this->_user}:{$this->_pass}@" : null;
        $port  = '80' !== $this->_port ? "{$this->_port}" : null;
        $query = null !== $this->_query ? "?{$this->_query}" : null;

        return "{$this->_scheme}://{$auth}{$this->_host}{$port}{$this->_path}{$query}";
    }

    /**
     * @return null
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return null
     */
    public function getPass()
    {
        return $this->_pass;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->_query;
    }
}