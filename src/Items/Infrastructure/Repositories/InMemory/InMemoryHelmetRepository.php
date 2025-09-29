<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Helmet;
use AqHub\Items\Domain\Repositories\HelmetRepository;
use AqHub\Items\Domain\Repositories\Data\HelmetData;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\{ItemInfo, Name, Description};
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use DateTime;

class InMemoryHelmetRepository implements HelmetRepository
{
    /** @var array<HelmetData> $memory */
    private array $memory = [];

    /**
     * @return Result<HelmetData|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Helmet::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Helmet with same identifier already exists: ' . $id->getValue(), null);
        }

        $helmetData = new HelmetData(
            $id,
            Name::create($itemInfo->getName())->unwrap(),
            Description::create($itemInfo->getDescription())->unwrap(),
            $itemInfo->tags,
            new DateTime()
        );

        $this->memory[$id->getValue()] = $helmetData;

        return Result::success(null, $helmetData);
    }

    /**
     * @return Result<HelmetData|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }
}
