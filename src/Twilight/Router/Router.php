<?php

declare(strict_types=1);

namespace Twilight\Router;

use Exception;
use Twilight\Router\RouterInterface;

class Router implements RouterInterface
{
    protected array $routes = [];
    protected array $params = [];
    protected string $controllerSuffix = 'controller';

    /**
     * @inheritDoc
     */
    public function add(string $route, array $params = []) : void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(string $url) : void
    {
        if ($this->match($url)) {
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNameSpace($controllerString);

            if (class_exists($controllerString)) {
                $controllerObject = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);

                if (\is_callable([$controllerObject, $action])) {
                    $controllerObject->$action();
                } else {
                    throw new Exception();
                }
            } else {
                throw new Exception();
            }
        } else {
            throw new Exception();
        }
    }
    
    /**
     * transformUpperCamelCase
     *
     * @param  mixed $string
     * @return string
     */
    public function transformUpperCamelCase(string $string) : string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    public function transformCamelCase(string $string) : string
    {
        return \lcfirst($this->transformUpperCamelCase($string));
    }

    /**
     * Match the route of url to the routes in the routing table, setting the $hits->params property
     * if a route is found
     *
     * @param string $url
     * @return bool
     */
    private function match(string $url) : bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $params) {
                    if (is_string($key)) {
                        $params[$key] = $params;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    /**
     * getNameSpace
     *
     * @param  mixed $string
     * @return string
     */
    public function getNameSpace(string $string) : string
    {
        $namespace = 'App\Controller\\';
        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
}