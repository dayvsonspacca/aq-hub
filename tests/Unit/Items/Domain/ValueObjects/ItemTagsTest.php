<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemTagsTest extends TestCase
{
    #[Test]
    public function should_create_item_tags_instance_and_stores_it_data()
    {
        $tags = new ItemTags([ItemTag::AdventureCoins]);

        $this->assertInstanceOf(ItemTags::class, $tags);
        $this->assertSame(1, $tags->count());
        $this->assertTrue($tags->has(ItemTag::AdventureCoins));
    }

    #[Test]
    public function should_can_add_a_tag()
    {
        $tags = new ItemTags([ItemTag::AdventureCoins]);
        $tags->add(ItemTag::Legend)->unwrap();

        $this->assertSame(2, $tags->count());
        $this->assertTrue($tags->has(ItemTag::Legend));
    }

    #[Test]
    public function should_fail_when_add_same_tag()
    {
        $tags   = new ItemTags([ItemTag::AdventureCoins]);
        $result = $tags->add(ItemTag::Legend);

        $this->assertTrue($result->isSuccess());

        $result = $tags->add(ItemTag::Legend);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($result->getMessage(), 'That item already have this tag.');
    }

    #[Test]
    public function should_can_iterate_in_items_tags()
    {
        $tags    = new ItemTags([ItemTag::AdventureCoins, ItemTag::Legend]);
        $rawTags = [ItemTag::AdventureCoins, ItemTag::Legend];

        foreach ($tags as $index => $tag) {
            $this->assertSame($tag, $rawTags[$index]);
        }
    }
}
