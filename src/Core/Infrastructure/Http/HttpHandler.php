<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use DI\Container;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\{RequestContext, Route as SymfonyRoute, RouteCollection};

final class HttpHandler
{
    private RouteCollection $routes;

    public function __construct(
        private readonly Container $container,
        private readonly array $controllers
    ) {
        $this->routes = $this->registerRoutes();
    }

    private function registerRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        foreach ($this->controllers as $controller) {
            $reflection = new ReflectionClass($controller);

            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attr) {
                    /** @var Route $routeAttr */
                    $routeAttr = $attr->newInstance();

                    $routeName = $reflection->getName() . '_' . $method->getName();

                    $routes->add(
                        $routeName,
                        new SymfonyRoute(
                            $routeAttr->path,
                            ['_controller' => [$controller, $method->getName()]],
                            [],
                            [],
                            '',
                            [],
                            $routeAttr->methods
                        )
                    );
                }
            }
        }

        return $routes;
    }

    public function handle(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher                    = new UrlMatcher($this->routes, $context);
        $parameters                 = $matcher->match($request->getPathInfo());
        [$controllerClass, $method] = $parameters['_controller'];

        $controller = $this->container->get($controllerClass);
        $response   = $controller->$method($request);

        return $response;
    }
}
