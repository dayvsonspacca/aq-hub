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
        if ($pageSize > 100) {
            $pageSize = 100;
        }

        $this->pageSize = $pageSize;
    }
}
