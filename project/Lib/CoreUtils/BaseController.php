<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 29/06/17
 * Time: 16:53
 */

namespace Lib\CoreUtils;

use Lib\HttpComponent\Request;
use Lib\HttpComponent\Response;
use Lib\Templating\Templater;

/**
 * Class BaseController
 * @package Lib\CoreUtils
 */
class BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function notFoundAction(Request $request)
    {
        return $this->render(':404.html.php', array('path' => $request->getPathInfo()));
    }

    /**
     * @param $template
     * @param null $params
     * @param int $code
     * @return Response
     */
    public function render($template, $params = null, $code = 200)
    {
        $fileContent = Templater::resolve($template);
        $content     = Templater::render($fileContent, $params);
        return new Response($content, $code);
    }
}