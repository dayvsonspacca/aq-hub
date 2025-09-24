<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use Symfony\Component\HttpFoundation\{Request, Response};
use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Shared\Infrastructure\Http\Route;

class PlayerController
{
    public function __construct(private readonly AddPlayer $addPlayer)
    {
    }

    #[Route(path: '/players', methods: ['GET'])]
    public function list(Request $request): Response
    {
        return new Response('List of players');
    }
}
