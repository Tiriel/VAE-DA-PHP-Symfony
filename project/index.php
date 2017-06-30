<?php

use Lib\CoreUtils\SplClassLoader;
use Lib\HttpComponent\Request;
use Lib\HttpComponent\Response;
use Lib\HttpComponent\Router;


require_once __DIR__ . '/Lib/CoreUtils/SplClassLoader.php';
$libLoader = new SplClassLoader('Lib', './Lib');
$libLoader->register();
$cacheLoader = new SplClassLoader('Cache', './Cache');
$cacheLoader->register();

$request  = new Request();
/* @var Response $response */
$response = Router::route($request);

$response->send();