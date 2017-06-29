<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 12:30
 */

namespace Lib\HttpComponent;

class ServerBag extends ParameterBag
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
     * ServerBag constructor.
     * @param $server
     */
    public function __construct($server)
    {
        parent::__construct($server);
        $this->_method = $this->get('REQUEST_METHOD', 'GET');
        $this->_scheme = $this->getScheme();
        $this->_user   = $this->get('PHP_AUTH_USER', null);
        $this->_pass   = $this->get('PHP_AUTH_PW', null);
        $this->_host   = $this->get('HTTP_HOST');
        $this->_port   = $this->getPort();
        $this->_path   = $this->get('SCRIPT_URL');
        $this->_query  = $this->get('QUERY_STRING');
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        $scheme = 'http';
        if (false !== $this->get('HTTPS') || 'https' === strtolower($this->get('HTTP_X_FORWARDED_PROTO'))) {
            $scheme = 'https';
        }
        return $scheme;
    }

    /**
     * @return null|string
     */
    public function getPort()
    {
        $port = '80' !== $this->get('SERVER_PORT') ? $this->get('SERVER_PORT') : null;

        $default = [
            null,
            '80',
            $this->get('SERVER_PORT')
        ];
        if ($this->get('HTTP_X_FORWARDED_PROTO') && !in_array($this->get('HTTP_X_FORWARDED_PROTO'), $default)) {
            $port = $this->get('HTTP_X_FORWARDED_PROTO');
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