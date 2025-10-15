<?php

declare(strict_types=1);

use AqHub\Core\Infrastructure\Http\{HttpHandler, HttpDefinitions};
use AqHub\Core\{Application, CoreDefinitions};
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$app     = Application::build('api', [CoreDefinitions::class, HttpDefinitions::class]);
$handler = $app->get(HttpHandler::class);

$request = Request::createFromGlobals();

$handler->handle($request);