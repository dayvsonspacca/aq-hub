<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use DI\Container;
use ReflectionClass;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\{RequestContext, Route as SymfonyRoute, RouteCollection};

class HttpHandler
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
                            [
                                '_controller' => [$controller, $method->getName()],
                                '_middlewares' => $routeAttr->middlewares
                            ],
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

    public function handle(Request $request): Response
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters = $matcher->match($request->getPathInfo());

            [$controllerClass, $method] = $parameters['_controller'];
            $middlewares = $parameters['_middlewares'] ?? [];

            $controllerInstance = $this->container->get(
                is_object($controllerClass) ? $controllerClass::class : $controllerClass
            );

            $coreAction = function (Request $req) use ($controllerInstance, $method): Response {
                return $controllerInstance->$method($req);
            };

            $pipeline = array_reduce(
                array_reverse($middlewares),
                function (callable $next, string $middlewareClass) {
                    return function (Request $req) use ($next, $middlewareClass): Response {
                        $middleware = $this->container->get($middlewareClass);
                        return $middleware->handle($req, $next);
                    };
                },
                $coreAction
            );

            $response = $pipeline($request);

            $this->addCorsHeaders($response);

            return $response;
        } catch (ResourceNotFoundException $e) {
            return new Response('Not Found', Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function addCorsHeaders(Response $response): void
    {
        $origin = '*';

        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
