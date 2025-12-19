<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Core\Result;
use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\ValueObjects\{Description, ItemInfo, ItemTags, Name};
use AqHub\Shared\Domain\Enums\ItemTag;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class AddArmorForm
{
    /** @return Result<ItemInfo> */
    public static function fromRequest(Request $request): Result
    {
        try {
            $payload = $request->toArray();

            $name        = Name::create($payload['name'] ?? '')->unwrap();
            $description = Description::create($payload['description'] ?? '')->unwrap();
            $rarity      = isset($payload['rarity']) ? ItemRarity::fromString($payload['rarity']) : null;
            $rarity      = !is_null($rarity) ? $rarity->unwrap() : null;
            $tags        = new ItemTags();

            foreach ($payload['tags'] ?? [] as $tag) {
                $itemTag = ItemTag::fromString($tag);
                if ($itemTag->isError()) {
                    continue;
                }

                $tags->add($itemTag->getData());
            }

            return Result::success(
                null,
                ItemInfo::create($name, $description, $tags, $rarity)->getData()
            );
        } catch (Exception $e) {
            return Result::error($e->getMessage(), null);
        }


    }
}
