<?php

declare(strict_types=1);

namespace Aqwiki\Domain\Repositories;

use AqWiki\Domain\{Entities, ValueObjects};

interface QuestRepositoryInterface
{
    public function getById(string $guid): ?Entities\Quest;
    public function persist(Entities\Quest $quest);
}
