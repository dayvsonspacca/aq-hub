<?php

declare(strict_types=1);

use AqHub\Core\Infrastructure\Http\{HttpHandler, HttpDefinitions};
use AqHub\Core\{Application, CoreDefinitions};
use AqHub\Core\Infrastructure\Database\DatabaseDefinitions;
use AqHub\Items\Infrastructure\Container\ItemsDefinitions;
use AqHub\Shared\Infrastructure\Container\SharedDefinitions;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

$app     = Application::build('api', [CoreDefinitions::class, HttpDefinitions::class, DatabaseDefinitions::class, ItemsDefinitions::class, SharedDefinitions::class]);
$handler = $app->get(HttpHandler::class);

$request = Request::createFromGlobals();
$response = $handler->handle($request);

$response->send();