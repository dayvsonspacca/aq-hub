<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Repositories\Filters;

trait HandlesPagination
{
    public int $page     = 1;
    public int $pageSize = 25;

    public function setPage(int $page)
    {
        $this->page = $page;
    }

    public function setPageSize(int $pageSize)
    {
        $this->pageSize = $pageSize;
    }
}
