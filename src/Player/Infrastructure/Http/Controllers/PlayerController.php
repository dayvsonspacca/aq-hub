<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Controllers;

use AqHub\Player\Application\UseCases\PlayerUseCases;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\ValueObjects\{Name};
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
        $this->cache = FileSystemCacheFactory::create('players-list', 60);
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
        $page = (int) $request->get('page', 1);

        if ($page <= 0) {
            return new JsonResponse(['message' => 'Param page cannot be zero or negative.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cacheKey = 'page-' . $page;
        $players = $this->cache->get($cacheKey, function (ItemInterface $item) use ($page) {
            $item->expiresAfter(60);

            $filter = new PlayerFilter(
                page: $page
            );

            $result = $this->playerUseCases->findAll->execute($filter);
            if ($result->isError()) {
                throw new RuntimeException($result->getMessage());
            }

            $players = $result->getData();
            $players = array_map(fn($player) => $player->toArray(), $players);
            
            $item->set($players);
            $item->tag('invalidate-on-new-player');

            return $players;
        });

        return new JsonResponse([
            'page' => $page,
            'players' => $players
        ], Response::HTTP_OK);
    }
}
