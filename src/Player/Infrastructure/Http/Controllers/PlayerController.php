<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Player\Application\UseCases\AddPlayer;
use AqHub\Shared\Infrastructure\Http\Route;

class PlayerController
{
    public function __construct(private readonly AddPlayer $addPlayer)
    {
    }

    #[Route(path: '/players/add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $post = $request->toArray() ?? [];

        $name = Name::create($post['name'] ?? '');
        if ($name->isError()) {
            return new JsonResponse(['message' => $name->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player = $this->addPlayer->execute($name->getData());
        if ($player->isError()) {
            return new JsonResponse(['message' => $player->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $player = $player->getData();

        return new JsonResponse($player->toArray(), Response::HTTP_OK);
    }
}
