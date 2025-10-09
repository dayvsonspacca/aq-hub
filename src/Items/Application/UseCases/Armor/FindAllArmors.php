<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Armor;

use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Shared\Infrastructure\Cache\FileSystemCacheFactory;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class FindAllArmors
{
    private FilesystemTagAwareAdapter $cache;

    public function __construct(private readonly ArmorRepository $armorRepository)
    {
        $this->cache = FileSystemCacheFactory::create('armors', 60);
    }

    /**
     * @return Result<array<ArmorData>>
     */
    public function execute(ArmorFilter $filter): Result
    {
        $cacheKey = $filter->generateUniqueKey();

        $cachedResult = $this->cache->get($cacheKey, function (ItemInterface $item) use ($filter) {

            $item->expiresAfter(60); 
            $item->tag('invalidate-on-new-armor');

            $result = $this->armorRepository->findAll($filter);

            if ($result->isError()) {
                return $result; 
            }
            
            $item->set($result);

            return $result;
        });

        return $cachedResult;
    }
}