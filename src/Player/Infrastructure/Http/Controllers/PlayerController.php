<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use AqHub\Player\Application\UseCases\{AddPlayer, FindAllPlayers};
use AqHub\Player\Domain\ValueObjects\{Name};
use AqHub\Player\Infrastructure\Repositories\Filters\PlayerFilter;
use AqHub\Shared\Infrastructure\Http\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class PlayerController
{
    public function __construct(
        private readonly AddPlayer $addPlayer,
        private readonly FindAllPlayers $findAllPlayers
    ) {
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

    #[Route(path: '/players/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);

        $filter = new PlayerFilter(
            page: $page
        );

        $players = $this->findAllPlayers->execute($filter);
        if ($players->isError()) {
            return new JsonResponse(['message' => $players->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $players = $players->getData();
        $players = array_map(fn ($player) => $player->toArray(), $players);

        return new JsonResponse([
            'page' => $page,
            'players' => $players
        ], Response::HTTP_OK);
    }
}
