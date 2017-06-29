<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 15:00
 */

namespace Lib;

use Lib\HttpComponent\Request;
use Lib\HttpComponent\Response;
use Lib\Templating\Templater;

class Controller
{
    public function indexAction(Request $request)
    {
        $name = $request->query->get('name', 'Ben');
        return $this->render(':index.html.php', array('name' => $name));
    }

    public function render($template, $params = null, $code = 200)
    {
        $fileContent = Templater::resolve($template);
        $content     = Templater::render($fileContent, $params);
        return new Response($content, $code);
    }
}