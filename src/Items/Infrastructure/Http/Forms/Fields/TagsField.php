<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Shared\Infrastructure\Http\Forms\EnumListProcessor;
use Symfony\Component\HttpFoundation\Request;

class TagsField
{
    /**
     * @return TagType[]
     */
    public static function fromRequest(Request $request): array
    {
        $tags = $request->get('tags', '');
        $tags = EnumListProcessor::fromComma($tags, TagType::class);

        return $tags;
    }
}
