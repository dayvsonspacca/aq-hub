<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Repositories\Filters\{ArmorFilter};
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ArmorFilterTest extends TestCase
{
    #[Test]
    public function should_create_armor_filter_with_default_values()
    {
        $filter = new ArmorFilter();

        $this->assertCount(5, get_object_vars($filter));

        $this->assertObjectHasProperty('name', $filter);
        $this->assertObjectHasProperty('page', $filter);
        $this->assertObjectHasProperty('pageSize', $filter);
        $this->assertObjectHasProperty('tags', $filter);
        $this->assertObjectHasProperty('rarities', $filter);
    }

    #[Test]
    public function should_can_return_array()
    {
        $filter = new ArmorFilter();

        $this->assertSame($filter->toArray(), [
            'page' => $filter->page,
            'page_size' => $filter->pageSize,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $filter->rarities),
            'tags' => array_map(fn ($tag) => $tag->toString(), $filter->tags),
            'name' => isset($filter->name) && !is_null($filter->name) ? $filter->name->value : null
        ]);
    }

    #[Test]
    public function should_can_generate_filter_unique_key()
    {
        $filter = new ArmorFilter();

        $this->assertSame($filter->generateUniqueKey(), '30bc93c6e5fb81fc894780d053feff40');
    }
}
