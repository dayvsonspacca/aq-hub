<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Infrastructure\Http\Forms\EnumListProcessor;
use Symfony\Component\HttpFoundation\Request;

class TagsField
{
    /**
     * @return ItemTag[]
     */
    public static function fromRequest(Request $request): array
    {
        $tags = $request->get('tags', '');
        $tags = EnumListProcessor::fromComma($tags, ItemTag::class);

        return $tags;
    }
}
