<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Routing\{RouteCollection, RequestContext};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Matcher\UrlMatcher;


$routes = new RouteCollection();

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($request->getPathInfo());
    [$controller, $method] = $parameters['_controller'] ?? [null, null];
    
    if ($controller && method_exists($controller, $method)) {
        $response = $controller->$method($request);
    } else {
        $response = new Response('Not Found', 404);
    }
} catch (\Throwable $th) {
    $response = new Response($th->getMessage(), $th->getCode() > 0 ? $th->getCode() : 500);
}

$response->send();
