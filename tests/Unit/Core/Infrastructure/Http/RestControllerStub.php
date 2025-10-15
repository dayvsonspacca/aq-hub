<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use Symfony\Component\HttpFoundation\Response;

final class RestControllerStub implements RestController
{
    #[Route(path: '/api/list', methods: ['GET'])]
    public function list()
    {
        return new Response(status: 200);
    }
}
