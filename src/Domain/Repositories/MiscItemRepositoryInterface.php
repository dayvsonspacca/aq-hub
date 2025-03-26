<?php

declare(strict_types=1);

namespace Aqwiki\Domain\Repositories;

use AqWiki\Domain\Entities;

interface MiscItemRepositoryInterface
{
    public function persist(Entities\MiscItem $miscItem);
    public function findByName(string $name): ?Entities\MiscItem;
}
