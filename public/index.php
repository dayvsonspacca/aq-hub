<?php

declare(strict_types=1);

use AqHub\Core\Infrastructure\Http\{HttpHandler, HttpDefinations};
use AqHub\Core\{Application, CoreDefinations};

require __DIR__ . '/../vendor/autoload.php';

$app     = Application::build('api', [CoreDefinations::class, HttpDefinations::class]);
$handler = $app->get(HttpHandler::class);

$handler->handle();