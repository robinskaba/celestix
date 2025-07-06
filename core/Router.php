<?php

namespace core;

class Router {
    protected $routes = [];

    public function get($url, $handler) {
        $this->routes[$url] = $handler;
    }

    public function dispatch() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Odeber "public" prefix pokud existuje (přesun URL do kořene)
        $base = ''; // uprav podle složky
        $url = str_starts_with($requestUri, $base) ? substr($requestUri, strlen($base)) : $requestUri;
        if ($url === '') $url = '/';

        if (isset($this->routes[$url])) {
            [$controller, $method] = $this->routes[$url];
            (new $controller)->$method();
        } else {
            http_response_code(404);
            echo "Not Found.";
        }
    }
}
