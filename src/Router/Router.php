<?php

namespace App\Router;

class Router {
    private $url;
    private $routes = array();
    private $namedRoutes = array();

    public function __construct(string $url) {
        $this->url = $url;
    }

    /**
     * Add a GET method route
     * @param string $path The path corresponding to the route
     * @param string|callable $callable The action to do when the route id called
     * @param string|null $name The route name to give a name to route
     * @return Route Returned the newly added route to make mehod chaining
     */
    public function get(string $path, mixed $callable, ?string $name = null): Route {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * Add a POST method route
     * @param string $path The path corresponding to the route
     * @param string|callable $callable The action to do when the route id called
     * @param string|null $name The route name to give a name to route
     * @return Route Returned the newly added route to make mehod chaining
     */
    public function post(string $path, mixed $callable, ?string $name = null): Route {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * Add a PATCH method route
     * @param string $path The path corresponding to the route
     * @param string|callable $callable The action to do when the route id called
     * @param string|null $name The route name to give a name to route
     * @return Route Returned the newly added route to make mehod chaining
     */
    public function patch(string $path, mixed $callable, ?string $name = null): Route {
        return $this->add($path, $callable, $name, 'PATCH');
    }

    /**
     * Add a DELETE method route
     * @param string $path The path corresponding to the route
     * @param string|callable $callable The action to do when the route id called
     * @param string|null $name The route name to give a name to route
     * @return Route Returned the newly added route to make mehod chaining
     */
    public function delete(string $path, mixed $callable, ?string $name = null): Route {
        return $this->add($path, $callable, $name, 'DELETE');
    }

    /**
     * Add a route for the given http request method
     * @param string $path The path corresponding to the route
     * @param string|callable $callable The action to do when the route id called
     * @param string|null $name The route name to give a name to route
     * @param string $method The HTTP request method
     * @return Route Returned the newly added route to make mehod chaining
     * @see Router::get
     * @see Router::post
     * @see Router::patch
     * @see Router::delete
     */
    private function add(string $path, mixed $callable, ?string $name, string $method): Route {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    /**
     * @throws RouterException If the REQUEST_METHOD is not supported or if
     *  there is no matching routes.
     */
    public function run(): mixed {
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException($_SERVER['REQUEST_METHOD'].' does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException('No matching routes for /'.$this->url);
    }

    /**
     * @throws RouterException if there is no routes with the given name
     */
    public function url(string $name, array $params = array()) {
        if(!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }
}