<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CapeFilterTest extends TestCase
{
    #[Test]
    public function should_create_armor_filter_with_default_values()
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

        $this->assertSame($filter->toArray(),  [
            'page' => $filter->page,
            'page_size' => $filter->pageSize,
            'rarities' => array_map(fn($rarity) => $rarity->toString(), $filter->rarities),
            'tags' => array_map(fn($tag) => $tag->toString(), $filter->tags),
            'name' => isset($filter->name) && !is_null($filter->name) ? $filter->name->value : null,
            'can_access_bank' => $filter->canAccessBank
        ]);
    }

    #[Test]
    public function should_can_generate_filter_unique_key()
    {
        $filter = new CapeFilter();

        $this->assertSame($filter->generateUniqueKey(), 'd1197bd5bc2dae71961f2bf9fa5553c3');
    }

    #[Test]
    public function should_can_change_can_access_bank_option()
    {
        $filter = new CapeFilter();
        $this->assertSame($filter->generateUniqueKey(), 'd1197bd5bc2dae71961f2bf9fa5553c3');

        $filter->setCanAccessBank(true);
        $this->assertSame($filter->generateUniqueKey(), '2374a29c391648844e9b862b65f27ffc');
    }
}