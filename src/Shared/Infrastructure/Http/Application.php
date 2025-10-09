<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http;

use AqHub\Shared\Infrastructure\Env\AppMode;
use AqHub\Shared\Infrastructure\Env\Env;
use DI\Container;
use Monolog\Logger;
use ReflectionClass;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Exception\{MethodNotAllowedException, ResourceNotFoundException};
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\{RequestContext, RouteCollection};
use Symfony\Component\Routing\Route as SymfonyRoute;

class Application
{
    private RouteCollection $routes;
    private array $controllers = [];
    private readonly Logger $logger;

    public function __construct(
        private readonly Container $container,
        private readonly Env $env
    ) {
        $this->routes = new RouteCollection();
        $this->controllers = require ROOT_PATH . '/config/controllers.php';
        $this->logger = $container->get('Logger.Api.Errors');

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
            $response = new JsonResponse(['message' => 'Method not allowed'], 405);
        } catch (\Throwable $e) {
            $this->logger->error('An unhandled exception occurred during request.', [
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'uri' => $request->getPathInfo(),
                'method' => $request->getMethod()
            ]);

            if ($this->env->appMode === AppMode::Production) {
                $response = new JsonResponse(['message' => 'Internal Server Error'], 500);
            } else {
                $response = new JsonResponse(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
            }
        }

        $response->send();
    }
}
