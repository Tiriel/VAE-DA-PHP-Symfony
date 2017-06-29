<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 29/06/17
 * Time: 11:55
 */

namespace Lib\HttpComponent;


/**
 * Class ParameterBag
 * @package Lib\HttpComponent
 */
class ParameterBag
{
    /**
     * @var array
     */
    private $_dump;

    /**
     * ParameterBag constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->_dump = $data;
    }

    /**
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    public function get($key, $default = null)
    {
        return $this->_dump[$key] ? : $default;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->_dump[$key] = $value;

        return $this;
    }
}