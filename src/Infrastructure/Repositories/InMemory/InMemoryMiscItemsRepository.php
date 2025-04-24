<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\InMemory;

use AqWiki\Domain\{Entities, Repositories, Exceptions};

final class InMemoryMiscItemsRepository implements Repositories\MiscItemRepositoryInterface
{
    private array $database = [];

    public function findById(string $guid): ?Entities\MiscItem
    {
        return $this->database[$guid] ?? null;
    }

    public function persist(Entities\MiscItem $miscItem)
    {
        if (isset($this->database[$miscItem->guid])) {
            throw Exceptions\RepositoryException::alreadyExists('InMemoryMiscItemsRepository');
        }

        $this->database[$miscItem->guid] = $miscItem;
    }
}
