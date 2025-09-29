<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Weapon;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Items\Domain\Repositories\Data\WeaponData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{ItemInfo, Name, Description};
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use DateTime;

class InMemoryWeaponRepository implements WeaponRepository
{
    /** @var array<WeaponData> $memory */
    private array $memory = [];

    /**
     * @return Result<WeaponData|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Weapon::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Weapon with same identifier already exists: ' . $id->getValue(), null);
        }

        $weaponData = new WeaponData(
            $id,
            Name::create($itemInfo->getName())->unwrap(),
            Description::create($itemInfo->getDescription())->unwrap(),
            $itemInfo->tags,
            $type,
            new DateTime()
        );

        $this->memory[$id->getValue()] = $weaponData;

        return Result::success(null, $weaponData);
    }

    /**
     * @return Result<WeaponData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }
}
