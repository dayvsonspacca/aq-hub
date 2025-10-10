<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use AqHub\Player\Application\UseCases\PlayerUseCases;
use AqHub\Player\Infrastructure\Http\Forms\{AddPlayerForm, ListPlayersForm};
use AqHub\Player\Infrastructure\Http\Presenters\PlayerPresenter;
use AqHub\Shared\Infrastructure\Http\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use RuntimeException;

class PlayerController
{
    public function __construct(
        private readonly PlayerUseCases $playerUseCases
    ) {}

    #[Route(path: '/players/add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $name = AddPlayerForm::fromRequest($request);
        if ($name->isError()) {
            return new JsonResponse(['message' => $name->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player = $this->playerUseCases->add->execute($name->getData());
        if ($player->isError()) {
            throw new RuntimeException($player->getMessage());
        }

        $player = $player->getData();

        return new JsonResponse($player->toArray(), Response::HTTP_OK);
    }

    #[Route(path: '/players/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $result = ListPlayersForm::fromRequest($request);
        if ($result->isError()) {
            return new JsonResponse(['message' => $result->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter = $result->getData();
        $players = $this->playerUseCases->findAll->execute($filter);

        if ($players->isError()) {
            throw new RuntimeException($players->getMessage());
        }

        $players = $players->getData();
        $players = PlayerPresenter::array($players);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'players' => $players
        ], Response::HTTP_OK);
    }
}
