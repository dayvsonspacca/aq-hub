<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\InMemory;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\Repositories\CapeRepository;
use AqHub\Items\Domain\Services\ItemIdentifierGenerator;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};

class InMemoryCapeRepository implements CapeRepository
{
    /** @var array<Cape> $memory description */
    private array $memory = [];

    /**
     * @return Result<Cape|null>
     */
    public function persist(ItemInfo $itemInfo): Result
    {
        $id = ItemIdentifierGenerator::generate($itemInfo, Cape::class)->unwrap();

        if ($this->findByIdentifier($id)->isSuccess()) {
            return Result::error('A Cape with same identifier already exists: ' . $id->getValue(), null);
        }

        $cape = Cape::create($id, $itemInfo)->unwrap();

        $this->memory[$cape->getId()] = $cape;

        return Result::success(null, $cape);
    }

    /**
     * @return Result<Cape|null>
     */
    public function findByIdentifier(StringIdentifier $identifier): Result
    {
        $capes = array_filter($this->memory, fn ($cape) => $cape->getId() === $identifier->getValue());

        if (empty($capes)) {
            return Result::error(null, null);
        }

        return Result::success(null, end($capes));
    }
}
