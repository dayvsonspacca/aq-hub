<?php

declare(strict_types=1);

namespace Tests\Unit\Items\Domain\ValueObjects;

use AqHub\Items\Domain\ValueObjects\ItemTags;
use AqHub\Shared\Domain\Enums\TagType;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ItemTagsTest extends TestCase
{
    #[Test]
    public function should_create_item_tags_instance_and_stores_it_data()
    {
        $tags = new ItemTags([TagType::AdventureCoins]);

        $this->assertInstanceOf(ItemTags::class, $tags);
        $this->assertSame(1, $tags->count());
        $this->assertTrue($tags->has(TagType::AdventureCoins));
    }

    #[Test]
    public function should_can_add_a_tag()
    {
        $tags = new ItemTags([TagType::AdventureCoins]);
        $tags->add(TagType::Legend)->unwrap();

        $this->assertSame(2, $tags->count());
        $this->assertTrue($tags->has(TagType::Legend));
    }

    #[Test]
    public function should_fail_when_add_same_tag()
    {
        $tags   = new ItemTags([TagType::AdventureCoins]);
        $result = $tags->add(TagType::Legend);

        $this->assertTrue($result->isSuccess());

        $result = $tags->add(TagType::Legend);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($result->getMessage(), 'That item already have this tag.');
    }

    #[Test]
    public function should_can_iterate_in_items_tags()
    {
        $tags   = new ItemTags([TagType::AdventureCoins, TagType::Legend]);
        $rawTags = [TagType::AdventureCoins, TagType::Legend];

        foreach ($tags as $index => $tag) {
            $this->assertSame($tag, $rawTags[$index]);
        }
    }
}
