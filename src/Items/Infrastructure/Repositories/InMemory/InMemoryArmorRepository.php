<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};

class InMemoryArmorRepository implements ArmorRepository
{
    /** @var array<Armor> $memory description */
    private array $memory = [];

    /**
     * @return Result<Armor|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Armor with same identifier already exists: ' . $id->getValue(), null);
        }

        $armor = Armor::create($id, $itemInfo)->unwrap();

        $this->memory[$armor->getId()] = $armor;

        return Result::success(null, $armor);
    }

    /**
     * @return Result<Armor|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $armors = array_filter($this->memory, fn ($armor) => $armor->getId() === $identifier->getValue());

        if (empty($armors)) {
            return Result::error(null, null);
        }

        return Result::success(null, end($armors));
    }
}
