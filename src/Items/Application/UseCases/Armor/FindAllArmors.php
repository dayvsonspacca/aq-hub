<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Armor;

use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Shared\Domain\ValueObjects\Result;

class FindAllArmors
{
    public function __construct(private readonly ArmorRepository $armorRepository)
    {
    }

    /**
     * @return Result<array<ArmorData>>
     */
    public function execute(ArmorFilter $filter): Result
    {
        return $this->armorRepository->findAll($filter);
    }
}
