<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 15:00
 */

namespace Lib;

use Lib\CoreUtils\BaseController;
use Lib\HttpComponent\Request;

/**
 * Class Controller
 * @package Lib
 */
class Controller extends BaseController
{
    /**
     * @param Request $request
     * @return HttpComponent\Response
     */
    public function indexAction(Request $request)
    {
        $name = $request->query->get('name', 'Ben');
        return $this->render(':index.html.php', array('name' => $name));
    }
}