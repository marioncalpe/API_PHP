<?php

namespace App\Router;

/**
 * Class defining an url route
 */
class Route {
    public static $twigEnv;

    private $path;

    /**
     * The action to do when the route is called.
     * @var callable|string The string must verify "Controller#method" pattern
     */
    private $callable;
    private $matches = array();
    private $params = array();

    public function __construct(string $path, mixed $callable) {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Permit to define a regex verification on url parameter
     * @param string $param The parameter to check
     * @param string $regex The regex to respect
     * @return Route Return the object for method chaining
     */
    public function with(string $param, string $regex): Route {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Permit to check if the route correspond to the given url
     * @param string $url The url to check
     * @return bool Return true if it match, otherwise return false
     */
    public function match(string $url): bool {
        $result = false;

        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if(preg_match($regex, $url, $matches)) {
            $result = true;
            array_shift($matches);
            $this->matches = $matches;
        }

        return $result;
    }

    /**
     * Parse the given paramterized arguments
     */
    private function paramMatch(array $match): string {
        $result = '([^/]+)';
        if(isset($this->params[$match[1]])) {
            $result = '('.$this->params[$match[1]].')';
        }

        return $result;
    }

    /**
     * Execute the given action
     * @see Route::callable
     * @return mixed Return the result of the callable attribute
     */
    public function call(): mixed {
        if(is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controller = "App\\Controller\\".$params[0]."Controller";
            $controller = new $controller(self::$twigEnv);

            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function getUrl(array $params): string {
        $path = $this->path;
        foreach($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }

        return $path;
    }
}