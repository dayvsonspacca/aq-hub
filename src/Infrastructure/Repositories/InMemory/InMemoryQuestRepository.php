<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\InMemory;

use AqWiki\Domain\{Entities, Repositories, Exceptions};

final class InMemoryQuestRepository implements Repositories\QuestRepositoryInterface
{
    private array $database = [];

    public function findById(string $guid): ?Entities\Quest
    {
        return $this->database[$guid] ?? null;
    }

    public function persist(Entities\Quest $quest)
    {
        if (isset($this->database[$quest->guid])) {
            throw Exceptions\RepositoryException::alreadyExists('InMemoryQuestRepository');
        }

        $this->database[$quest->guid] = $quest;
    }
}
