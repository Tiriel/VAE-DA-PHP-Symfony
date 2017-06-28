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
    public static function resolve($name)
    {
        $template = file_get_contents($name);
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
        $content = self::replace($template);
        return eval($content);
    }

    /**
     * @param $template
     * @return array|NUll
     */
    private function replace($template)
    {
        return preg_replace_callback_array([
            '/{{\s?(\w+)[.\w]*\s?}}/'                      => function ($matches) { return $this->varToken($matches); },
            '/{%\s?if\s?(\w+)\s?([=!]+)\s?(\w+)\s?%}/'     => function ($matches) { return $this->ifToken($matches); },
            '/{%\s?elseif\s?(\w+)\s?([=!]+)\s?(\w+)\s?%}/' => function ($matches) { return "} else" . $this->ifToken($matches); },
            '/{%\s?else\s?%}/'                             => function ($matches) { return "} else {"; },
            '/{%\s?endif\s?%}/'                            => function ($matches) { return "}"; },
            '/{%\s?for\s?(\w+)\sin\s(\w+)\s?%}/'           => function ($matches) { return "foreach ({$matches[2]} as {$matches[1]}) {"; },
            '/{%\s?endfor\s?%}/'                           => function ($matches) { return "}"; },
        ], $template);
    }

    private function varToken($matches)
    {
        if (false === strpos($matches, '.')) {
            return "\${$matches[1]}";
        }
    }

    /**
     * @param $matches
     * @return string
     */
    private function ifToken($matches)
    {
        $first  = ('true|false' == $matches[1]) || ctype_digit($matches[1]) ? $matches[1] : "\${$matches[1]}" ;
        $second = ('true|false' == $matches[3]) || ctype_digit($matches[3]) ? $matches[3] : "\${$matches[3]}" ;
        return "if ($first {$matches[2]} $second) {";
    }
}