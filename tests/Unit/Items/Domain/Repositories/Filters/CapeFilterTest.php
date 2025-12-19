<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CapeFilterTest extends TestCase
{
    #[Test]
    public function should_create_cape_filter_with_default_values()
    {
        $filter = new CapeFilter();

        $this->assertCount(6, get_object_vars($filter));

        $this->assertObjectHasProperty('name', $filter);
        $this->assertObjectHasProperty('page', $filter);
        $this->assertObjectHasProperty('pageSize', $filter);
        $this->assertObjectHasProperty('tags', $filter);
        $this->assertObjectHasProperty('rarities', $filter);
        $this->assertObjectHasProperty('canAccessBank', $filter);
    }

    #[Test]
    public function should_can_return_array()
    {
        $filter = new CapeFilter();

        $this->assertSame($filter->toArray(), [
            'page' => $filter->page,
            'page_size' => $filter->pageSize,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $filter->rarities),
            'tags' => array_map(fn ($tag) => $tag->toString(), $filter->tags),
            'name' => isset($filter->name) && !is_null($filter->name) ? $filter->name->value : null,
            'can_access_bank' => $filter->canAccessBank
        ]);
    }

    #[Test]
    public function should_can_generate_filter_unique_key()
    {
        $filter = new CapeFilter();

        $this->assertSame($filter->generateUniqueKey(), 'c397704d57c75a16e635541e02937127');
    }
}
