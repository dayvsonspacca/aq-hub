<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Infrastructure\Http\Forms\Fields\TagsField;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\Unit\TestRequests;
use PHPUnit\Framework\Attributes\Test;

final class TagsFieldTest extends TestRequests
{
    #[Test]
    public function should_ignore_invalid_tags_and_filter_by_valid_ones()
    {
        $request = $this->createRequest(['tags' => 'Legend,ac,rar']);

        $tags = TagsField::fromRequest($request);

        $this->assertCount(2, $tags);
        $this->assertSame(array_values($tags), [ItemTag::Legend, ItemTag::AdventureCoins]);
    }
}
