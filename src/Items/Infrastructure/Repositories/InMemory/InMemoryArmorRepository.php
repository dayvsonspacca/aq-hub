<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Armor;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{ItemInfo, Name, Description};
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use DateTime;

class InMemoryArmorRepository implements ArmorRepository
{
    /** @var array<ArmorData> $memory description */
    private array $memory = [];

    /**
     * @return Result<ArmorData|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Armor::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('An Armor with same identifier already exists: ' . $id->getValue(), null);
        }

        $armorData = new ArmorData(
            Name::create($itemInfo->getName())->unwrap(),
            Description::create($itemInfo->getDescription())->unwrap(),
            $itemInfo->getTags(),
            new DateTime()
        );

        $this->memory[$id->getValue()] = $armorData;

        return Result::success(null, $armorData);
    }

    /**
     * @return Result<ArmorData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }
}
