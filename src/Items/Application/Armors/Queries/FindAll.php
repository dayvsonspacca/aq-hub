<?php

declare(strict_types=1);

namespace AqHub\Items\Application\Armors\Queries;

use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;

class FindAll
{
    public function __construct(private readonly ArmorRepository $repository)
    {
    }

    public function execute(ArmorFilter $filter)
    {
        return $this->repository->findAll($filter);
    }
}
