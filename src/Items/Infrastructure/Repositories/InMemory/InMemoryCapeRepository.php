<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{ItemInfo, Name, Description};
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use DateTime;

class InMemoryCapeRepository implements CapeRepository
{
    /** @var array<CapeData> $memory */
    private array $memory = [];

    /**
     * @return Result<CapeData|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Cape with same identifier already exists: ' . $id->getValue(), null);
        }

        $capeData = new CapeData(
            Name::create($itemInfo->getName())->unwrap(),
            Description::create($itemInfo->getDescription())->unwrap(),
            $itemInfo->getTags(),
            new DateTime()
        );

        $this->memory[$id->getValue()] = $capeData;

        return Result::success(null, $capeData);
    }

    /**
     * @return Result<CapeData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }
}
