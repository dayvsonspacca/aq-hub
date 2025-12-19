<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Shared\Domain\Repositories\{CanCountAll, CanFindAll, CanFindByIdentifier};
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;

/**
 * @method CapeData|null findByIdentifier(StringIdentifier $identifier)
 * @method CapeData[] findAll(CapeFilter $filter)
 * @method int countAll(CapeFilter $filter)
 */
interface CapeRespository extends CanFindByIdentifier, CanFindAll, CanCountAll
{
    public function hydrate(array $data): CapeData;
}
