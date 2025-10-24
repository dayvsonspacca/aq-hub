<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\HandlesPagination;
use AqHub\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class HandlesPaginationTest extends TestCase
{
    #[Test]
    public function should_create_can_paginate_filter_with_default_values()
    {
        $filter = new class () {
            use HandlesPagination;
        };

        $this->assertSame(1, $filter->page);
        $this->assertSame(25, $filter->pageSize);
    }

    #[Test]
    public function should_change_the_page_and_page_size()
    {
        $filter = new class () {
            use HandlesPagination;
        };

        $this->assertSame(1, $filter->page);
        $this->assertSame(25, $filter->pageSize);

        $filter->setPage(2);
        $filter->setPageSize(50);

        $this->assertSame(2, $filter->page);
        $this->assertSame(50, $filter->pageSize);
    }

    #[Test]
    public function should_set_page_size_to_max_if_greater_than_max()
    {
        $filter = new class () {
            use HandlesPagination;
        };

        $filter->setPageSize(2549);

        $this->assertSame(100, $filter->pageSize);
    }
}
