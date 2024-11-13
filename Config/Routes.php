<?php

class Routes
{
    private static $routes = [];

    public static function Add($url, $controller, $method)
    {
        self::$routes[] = [
            'url' => $url,
            'controller' => $controller,
            'method' => $method
        ];
    }

    public static function Run()
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        $url_parts = explode('?', $request_uri, 2);
        $path = $url_parts[0];
    
        foreach (self::$routes as $route) {
            if ($path == $route['url']) {
                $controller_name = 'App\\Controllers\\' . $route['controller'];
                $method_name = $route['method'];
    
                if (class_exists($controller_name)) {
                    $controller = new $controller_name();
                    if (method_exists($controller, $method_name)) {
                        $controller->$method_name();
                    } else {
                        echo "Method $method_name not found in controller $controller_name";
                    }
                } else {
                    echo "Controller $controller_name not found";
                }
                return;
            }
        }
        echo "404 - Page not found";
    }

}
