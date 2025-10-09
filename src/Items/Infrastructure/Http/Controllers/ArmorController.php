<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers;

use AqHub\Items\Application\UseCases\Armor\ArmorUseCases;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Shared\Infrastructure\Cache\FileSystemCacheFactory;
use AqHub\Shared\Infrastructure\Http\Route;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Contracts\Cache\ItemInterface;

class ArmorController
{
    private FilesystemTagAwareAdapter $cache;

    public function __construct(
        private readonly ArmorUseCases $armorUseCases
    ) {
        $this->cache = FileSystemCacheFactory::create('armors', 60);
    }

    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);

        if ($page <= 0) {
            return new JsonResponse(['message' => 'Param page cannot be zero or negative.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cacheKey = 'page-' . $page;
        $armors = $this->cache->get($cacheKey, function (ItemInterface $item) use ($page) {
            $item->expiresAfter(60);

            $filter = new ArmorFilter(
                page: $page
            );

            $result = $this->armorUseCases->findAll->execute($filter);
            if ($result->isError()) {
                throw new RuntimeException($result->getMessage());
            }

            $armors = $result->getData();
            $armors = array_map(fn($armor) => $armor->toArray(), $armors);
            
            $item->set($armors);
            $item->tag('invalidate-on-new-armor');

            return $armors;
        });

        return new JsonResponse([
            'page' => $page,
            'armors' => $armors
        ], Response::HTTP_OK);
    }
}
