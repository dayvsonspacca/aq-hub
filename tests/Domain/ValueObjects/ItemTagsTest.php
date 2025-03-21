<?php

declare(strict_types=1);

namespace Tests\Domain\ValueObjects;

use AqWiki\Domain\{ValueObjects, Enums, Exceptions};
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\TestCase;

final class ItemTagsTest extends TestCase
{
    #[Test]
    public function should_can_add_a_tag()
    {
        $tags = new ValueObjects\ItemTags([Enums\TagType::Legend]);
        $preAdd = $tags->count();
        $tags->add(Enums\TagType::AdventureCoins);

        $this->assertNotEquals($tags->count(), $preAdd);
    }

    #[Test]
    public function should_fail_because_tag_already_exists()
    {
        $this->expectException(Exceptions\AqwItemException::class);
        $this->expectExceptionMessage('The tag `Legend` is already in the item tags.');

        $tags = new ValueObjects\ItemTags([Enums\TagType::Legend]);
        $tags->add(Enums\TagType::Legend);
    }

    #[Test]
    public function should_find_tag_in_list()
    {
        $tags = new ValueObjects\ItemTags([Enums\TagType::Legend, Enums\TagType::AdventureCoins, Enums\TagType::Rare]);

        $this->assertTrue($tags->has(Enums\TagType::Rare));
    }
}
