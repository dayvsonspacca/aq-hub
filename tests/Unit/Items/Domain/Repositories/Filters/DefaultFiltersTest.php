<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Filters\DefaultFilters;
use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class DefaultFiltersTest extends TestCase
{
    #[Test]
    public function should_create_default_filters()
    {
        $filter = new class () {
            use DefaultFilters;
        };

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
        $filter = new class () {
            use DefaultFilters;

            public function toArray(): array
            {
                return $this->defaultsArray();
            }
        };

        $this->assertSame($filter->toArray(), [
            'page' => $filter->page,
            'page_size' => $filter->pageSize,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $filter->rarities),
            'tags' => array_map(fn ($tag) => $tag->toString(), $filter->tags),
            'name' => isset($filter->name) && !is_null($filter->name) ? $filter->name->value : null
        ]);
    }


    #[Test]
    public function should_can_generate_filter_unique_key_without_change_values()
    {
        $filter = new class () {
            use DefaultFilters;

            public function generateUniqueKey(): string
            {
                return md5($this->defaultsUniqueKey());
            }
        };

        $this->assertSame($filter->generateUniqueKey(), '30bc93c6e5fb81fc894780d053feff40');
    }

    #[Test]
    public function should_can_generate_filter_unique_key_changing_values()
    {
        $filter = new class () {
            use DefaultFilters;

            public function generateUniqueKey(): string
            {
                return md5($this->defaultsUniqueKey());
            }
        };

        $filter->setRarities([ItemRarity::Epic]);
        $filter->setName(Name::create('Archfiend Doomlord')->unwrap());
        $filter->setTags([ItemTag::AdventureCoins]);

        $this->assertSame($filter->generateUniqueKey(), '5e0541a095e2b8d0a0caa81b7b885bc5');
    }
}
