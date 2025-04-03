<?php

require __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Routing\{Route, RouteCollection, RequestContext};
use Symfony\Component\HttpFoundation\{Request, Response};
use AqWiki\Application\Controllers\MiscItemController;
use Symfony\Component\Routing\Matcher\UrlMatcher;


$routes = new RouteCollection();
$routes->add('misc_item_add', new Route(
    path: '/misc-item/add',
    methods: ['POST'],
    defaults: ['_controller' => [new MiscItemController(), 'add']]
));

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
