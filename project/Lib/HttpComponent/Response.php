<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 15:06
 */

namespace Lib\HttpComponent;


use Lib\Exceptions\InternalErrorException;

/**
 * Class Response
 * @package Lib\HttpComponent
 */
class Response
{
    /**
     * @var array
     */
    private $httpStatus = [
        200 => 'OK',
        201 => 'Created',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    /**
     * @var string
     */
    private $_version;

    /**
     * @var string
     */
    private $_code;

    /**
     * @var string
     */
    private $_text;

    /**
     * @var array
     */
    private $_headers;

    /**
     * @var mixed
     */
    private $_content;

    /**
     * Response constructor.
     * @param $content
     * @param int $code
     * @param array $headers
     */
    public function __construct($content, $code = 200, array $headers = [])
    {
        $this->setVersion();
        $this->setCode($code);
        $this->setText();
        $this->setContent($content);
        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                $this->setHeader($name, $value, false);
            }
        }
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version = '1.1')
    {
        if (!in_array($version, array('1.0', '1.1', '2.0'))) {
            throw new InternalErrorException("Unsupported or deprecated HTTP version : $version");
        }
        $this->_version = $version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code = 200)
    {
        if (!array_key_exists($code, $this->httpStatus)) {
            throw new InternalErrorException("The status code $code is not recognized.");
        }
        $this->_code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     */
    public function setText($status = null)
    {
        if (ctype_digit($status)) {
            $text = $this->httpStatus[$status];
        } elseif (ctype_print($status)) {
            $text = $status;
        } else {
            $text = $this->httpStatus[$this->_code];
        }

        $this->_text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @param $key
     * @param $value
     * @param bool $replace
     * @return $this
     */
    public function setHeader($key, $value, $replace = false)
    {
        if (false === $replace && array_key_exists($key, $this->_headers)) {
            $this->_headers[$key] = array_merge($this->_headers[$key], array($value));
        } else {
            $this->_headers[$key] = array($value);
        }

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function removeHeader($key, $value)
    {
        if (!array_key_exists($key, $this->_headers)) {
            return $this;
        }
        $pos = array_search($value, $this->_headers[$key]);
        if (false === $pos) {
            return $this;
        }
        unset($this->_headers[$key][$pos]);

        if (empty($this->_headers[$key])) {
            unset($this->_headers[$key]);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * Send headers
     */
    public function sendHeaders()
    {
        header(sprintf('HTTP/%s %s %s', $this->_version, $this->_code, $this->_text), false, $this->_code);

        foreach ($this->_headers as $header => $values) {
            foreach ($values as $value) {
                header($header . ': ' . $value, false, $this->_code);
            }
        }
    }

    /**
     * Send content
     */
    public function sendContent()
    {
        echo $this->_content;
    }

    /**
     * Send response headers and content
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }
}