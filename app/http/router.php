class Router {
    private $routes = [];

    public function get($path, $handler) {
        $this->routes[] = [
            'method' => 'GET',
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handleRequest($method, $path) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    $handler();
                } else {
                    list($controllerClass, $method) = explode('@', $handler);
                    $controller = new $controllerClass();
                    $controller->$method();
                }
                return;
            }
        }
        http_response_code(404);
        echo '404 Not Found';
    }
}
