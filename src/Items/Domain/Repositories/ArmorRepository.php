<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Core\Result;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\ValueObjects\{ItemInfo};
use AqHub\Shared\Domain\Repositories\{CanFindAll, CanFindByIdentifier};
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;

/**
 * @method ArmorData|null findByIdentifier(StringIdentifier $identifier)
 * @method ArmorData[] findAll(ArmorFilter $filter)
 */
interface ArmorRepository extends CanFindByIdentifier, CanFindAll
{
    public function hydrate(array $data): ArmorData;
    public function save(ItemInfo $info): Result;
}
