<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 17:47
 */

namespace Lib\Templating;

/**
 * Class Templater
 * @package Lib\Templating
 */
class Templater
{
    /**
     * @param $name
     * @return bool|string
     */
    public static function resolve($identifier)
    {
        list($dir, $name) = explode(':', $identifier);

        $dir  = '' == $dir ? $dir : $dir.'/';
        $path = __DIR__.'/web/views/'.$dir.$name;

        $template = file_get_contents($path);
        return $template;
    }

    /**
     * @param $template
     * @param null $params
     * @return mixed
     */
    public static function render($template, $params = null)
    {
        if (null !== $params) {
            extract($params);
        }
        return eval($template);
    }
}