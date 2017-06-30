<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 28/06/17
 * Time: 14:53
 */

namespace Lib\HttpComponent;

use Lib\Controller;
use Cache\RoutingCache;

/**
 * Class Router
 * @package Lib\HttpComponent
 */
class Router
{
    const ROUTING_CACHE = __DIR__.'/Cache/RoutingCache.php';
    const ROUTING_XML   = __DIR__.'/config/routing.xml';

    /**
     * @param Request $request
     * @return mixed
     */
    public static function route(Request $request)
    {
        if (!file_exists(self::ROUTING_CACHE) || !class_exists('RoutingCache')) {
            self::buildCache();
        }

        require_once self::ROUTING_CACHE;

        if (!RoutingCache::isValid()) {
            self::buildCache();
        }

        $path    = strtolower($request->getPathInfo());
        $action  = RoutingCache::matchPath($path, $request->getMethod());
        $action .= 'Action';
        $controller = new Controller();
        return $controller->$action();
    }

    /**
     * @return int
     */
    public static function buildCache()
    {
        $routing = simplexml_load_file(self::ROUTING_XML);
        $config  = $routing->config;
        $routes  = $routing->routes->children();

        $now       = time();
        $cacheTime = $now + $config->cacheTime;
        $fileContent = '';

        foreach ($routes as $route) {
            $method = strtoupper($route->method);
            $andMethod = isset($method) ? "'".$method."'" : '\'GET\'';
            $methodArray = "'".implode("', '", explode(',', $andMethod))."'";

            //
            $routingSnippet =
<<<EOD

        if ('$route->path' === \$pathinfo) {
            \$allowedMethods = array($methodArray);
            if (!in_array(\$method, \$allowedMethods)) {
                throw new HttpMethodNotAllowedException(\$allowedMethods);
            }
            return '$route->action';
        }
EOD;
            // Adding snippet to global content
            $fileContent .= $routingSnippet;
        }

        // Building file content
        $content =
<<<EOD
<?php

namespace Cache;

use Lib\Exceptions\HttpMethodNotAllowedException;

class RoutingCache
{
    /**
     * @var int
     */
    const CACHE_TIME = $cacheTime;
    
    /**
     * Returns if cache is still valid
     * @return bool
     */
    public static function isValid()
    {
        return time() < self::CACHE_TIME;
    }
    
    /**
     * Try to match request path with routing file
     */
    public static function matchPath(\$path, \$method)
    {
        \$pathinfo = rawurldecode(\$path);
        
        $fileContent
        
        return 'notFound';
    }
}

EOD;

        try {
            file_put_contents(self::ROUTING_CACHE, $content);
        } catch (\Exception $e) {
            return intval($e->getCode());
        }
        return 0;
    }
}