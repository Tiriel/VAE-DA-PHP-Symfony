<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 17:47
 */

namespace Lib\Templating;

class Templater
{
    public static function resolve($name)
    {
        $template = file_get_contents($name);
        return $template;
    }

    public static function render($template, $params = null)
    {
        if (null !== $params) {
            extract($params);
        }
        $content = preg_replace('#{{\s?(\w+)\s?}}#', '\$$1', $template);
        return eval($content);
    }
}