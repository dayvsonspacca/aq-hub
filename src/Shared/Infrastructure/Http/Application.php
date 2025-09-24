<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\{RouteCollection, RequestContext};
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use ReflectionClass;
use DI\Container;

class Application
{
    private array $controllers = [];
    private RouteCollection $routes;

    public function __construct(private Container $container)
    {
        $this->routes = new RouteCollection();
    }

    public function registerControllers(array $controllers): void
    {
        $this->controllers = $controllers;
        $this->routes      = $this->generateRoutesFromControllers();
    }

    private function generateRoutesFromControllers(): RouteCollection
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

    public function handle(): void
    {
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters                 = $matcher->match($request->getPathInfo());
            [$controllerClass, $method] = $parameters['_controller'];

            $controller = $this->container->get($controllerClass);

            $response = $controller->$method($request);
        } catch (ResourceNotFoundException) {
            $response = new JsonResponse(['message' => 'Not Found'], 404);
        } catch (MethodNotAllowedException) {
            $response = new JsonResponse(['message' => 'Not Found'], 404);
        } catch (\Throwable $e) {
            $response = new JsonResponse(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }

        $response->send();
    }
}
