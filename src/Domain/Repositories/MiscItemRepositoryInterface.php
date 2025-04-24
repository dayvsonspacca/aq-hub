<?php

declare(strict_types=1);

namespace AqWiki\Domain\Repositories;

use AqWiki\Domain\Entities;

interface MiscItemRepositoryInterface
{
    public function findById(string $guid): ?Entities\MiscItem;
    public function persist(Entities\MiscItem $miscItem);
}
