<?php

declare(strict_types=1);

namespace AqWiki\Infrastructure\Repositories\Fakes;

use AqWiki\Domain\{Entities, Repositories, ValueObjects, Enums, Exceptions};

final class FakeMiscItemsRepository implements Repositories\MiscItemRepositoryInterface
{
    private array $database;

    public function __construct()
    {
        $miscItem = new Entities\MiscItem();
        $miscItem
            ->defineName('Dark Crystal Shard')
            ->defineSellback(new ValueObjects\GameCurrency(0, Enums\CurrencyType::AdventureCoins))
            ->defineDescription(
                'A small, refined shard imbued with dark magic. Significantly more valuable than a Tainted Gem.'
            );

        $this->database = [
            'Dark Crystal Shard' => $miscItem
        ];
    }

    public function persist(Entities\MiscItem $miscItem)
    {
        if (isset($this->database[$miscItem->getName()])) {
            throw Exceptions\RepositoryException::alreadyExists(__CLASS__);
        }
        $this->database[$miscItem->getName()] = $miscItem;
    }

    public function findByName(string $name): ?Entities\MiscItem
    {
        if (!isset($this->database[$name])) {
            return null;
        }

        return $this->database[$name];
    }
}
