<?php

use Lib\CorePhp\SplClassLoader;
use Lib\HttpComponent\Request;
use Lib\HttpComponent\Response;
use Lib\HttpComponent\Router;
use Lib\Controller;


require_once __DIR__.'/Lib/CorePhp/SplClassLoader.php';
$loader = new SplClassLoader('Lib', './Lib');
$loader->register();

$request = new Request();
$action  = Router::route($request);

$controller = new Controller();
/* @var Response $response */
$response   = $controller->$action($request);

$response->send();