<?php
class Dispatcher {
    private $routes;

    public function __construct() {
        $this->routes = require __DIR__ . '/routes.php';
    }

    public function dispatch($url) {
        $url = trim($url, '/');

        // Special handling for search routes
        if ($url == 'carros/buscar') {
            require_once __DIR__ . "/controllers/CarroController.php";
            $controller = new CarroController();
            $controller->buscar();
            return;
        }

        if (isset($this->routes[$url])) {
            $controllerName = $this->routes[$url]['controller'] . 'Controller';
            $method = $this->routes[$url]['method'];
        } else {
            $parts = explode('/', $url);
            $controllerName = ucfirst($parts[0] ?? 'Carro') . 'Controller';
            $method = $parts[1] ?? 'index';
        }

        $controllerFile = __DIR__ . "/controllers/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                echo "Método {$method} não encontrado em {$controllerName}";
            }
        } else {
            echo "Controller não encontrado: {$controllerFile}";
        }
    }
}
?>
