<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Repositories\Filters\CanFilterTags;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CanFilterTagsTest extends TestCase
{
    #[Test]
    public function should_create_can_filter_tags_with_default_values()
    {
        $filter = new class () {
            use CanFilterTags;
        };

        $this->assertEquals([], $filter->tags);
    }

    #[Test]
    public function should_can_change_tags()
    {
        $filter = new class () {
            use CanFilterTags;
        };

        $this->assertEquals([], $filter->tags);

        $tags = [ItemTag::AdventureCoins, ItemTag::Legend];
        $filter->setTags($tags);

        $this->assertSame($filter->tags, $tags);
    }
}
