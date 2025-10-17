<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Traits;

use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Shared\Domain\Helpers\EnumListProcessor;
use Symfony\Component\HttpFoundation\Request;

trait MapsTags
{
    /**
     * @return ItemTag[]
     */
    private static function mapTags(Request $request): array
    {
        $tags = $request->get('tags', '');
        $tags = EnumListProcessor::fromComma($tags, ItemTag::class);

        return $tags;
    }
}
