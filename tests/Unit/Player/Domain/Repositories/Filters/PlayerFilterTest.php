<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Player\Domain\Repositories\Filters;

use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Tests\Unit\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class PlayerFilterTest extends TestCase
{
    #[Test]
    public function should_create_player_filter_with_default_values()
    {
        $filter = new PlayerFilter();

        $this->assertCount(3, get_object_vars($filter));

        $this->assertObjectHasProperty('page', $filter);
        $this->assertObjectHasProperty('pageSize', $filter);
        $this->assertObjectHasProperty('mined', $filter);
    }

    #[Test]
    public function should_can_generate_filter_unique_key()
    {
        $filter = new PlayerFilter();

        $this->assertSame($filter->generateUniqueKey(), '30bc93c6e5fb81fc894780d053feff40');
    }

    #[Test]
    public function should_can_return_array()
    {
        $filter = new PlayerFilter();

        $this->assertSame($filter->toArray(), [
            'page' => $filter->page,
            'page_size' => $filter->pageSize,
            'mined' => $filter->mined
        ]);
    }

    #[Test]
    public function should_can_change_mined_option()
    {
        $filter = new PlayerFilter();

        $this->assertSame($filter->generateUniqueKey(), '30bc93c6e5fb81fc894780d053feff40');

        $filter->isMined(true);

        $this->assertSame($filter->mined, true);
        $this->assertSame($filter->generateUniqueKey(), '32a0ae2e2d58162ddbce86963a2fc975');
    }
}
