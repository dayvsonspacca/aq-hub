<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use AqHub\Player\Application\UseCases\PlayerUseCases;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Infrastructure\Http\Forms\ListPlayersForm;
use AqHub\Player\Infrastructure\Http\Presenters\PlayerPresenter;
use AqHub\Shared\Infrastructure\Cache\FileSystemCacheFactory;
use AqHub\Shared\Infrastructure\Http\Route;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Contracts\Cache\ItemInterface;

class PlayerController
{
    private FilesystemTagAwareAdapter $cache;

    public function __construct(
        private readonly PlayerUseCases $playerUseCases
    ) {
        $this->cache = FileSystemCacheFactory::create('players', 0);
    }

    #[Route(path: '/players/add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $post = $request->toArray() ?? [];

        $name = Name::create($post['name'] ?? '');
        if ($name->isError()) {
            return new JsonResponse(['message' => $name->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player = $this->playerUseCases->add->execute($name->getData());
        if ($player->isError()) {
            return new JsonResponse(['message' => $player->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->cache->invalidateTags(['invalidate-on-new-player']);

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
