<?php


class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function run() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$uri])) {
            $this->routes[$method][$uri]();
        } else {
            http_response_code(404);
            echo "Route not found";
        }
    }
}