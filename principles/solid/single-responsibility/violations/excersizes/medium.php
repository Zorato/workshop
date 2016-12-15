<?php

class RouteCollection {

    /**
     * @var Route[]
     */
    private $routes = [];

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        // Return only routes for current domain.
        return array_filter($this->routes, function($route) {
            return Request::getHost() === $route->getHost();
        });
    }

    public function addRoute(Route $route)
    {
        // Normalize routes
        $this->normalizeRoute($route);
        if ($route->getName()) {
            foreach ($this->routes as $r) {
                // Don't allow route names to duplicate
                if ($r->getName() === $route->getName()) {
                    throw new InvalidArgumentException('Route named "'.$r->getName().'" already defined!');
                }
            }
        }
        $this->routes[] = $route;
    }

    protected function normalizeRoute(Route $route)
    {
        if (empty($route->getHost())) {
            $route->setHost(Request::getHost());
        }
        if (mb_substr($route->getUri(), 0, 1) !== '/') {
            $route->setUri('/' . $route->getUri());
        }
    }
}