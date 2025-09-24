<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Services;

use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};

class ItemIdentifierGenerator
{
    /**
     * Generates a deterministic string identifier for an item.
     *
     * @param ItemInfo $itemInfo The item's information.
     * @param string $className The class/type of the item (e.g., Weapon, Armor).
     *
     * @return Result<StringIdentifier> The generated identifier wrapped in a Result.
     */
    public static function generate(ItemInfo $itemInfo, string $className): Result
    {
        $tagsString = implode(',', $itemInfo->getTags()->toArray());
        $dataString = $className . '|' . $itemInfo->getName() . '|' . $itemInfo->getDescription() . '|' . $tagsString;

        $hash = hash('sha256', $dataString);

        return StringIdentifier::create($hash);
    }
}
