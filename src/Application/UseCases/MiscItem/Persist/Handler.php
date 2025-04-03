<?php

declare(strict_types=1);

namespace AqWiki\Application\UseCases\MiscItem\Persist;

use AqWiki\Domain\{Entities, Repositories};

final class Handler
{
    public function __construct(private Repositories\MiscItemRepositoryInterface $miscItemRepository)
    {
    }

    public function handle(Entities\MiscItem $miscItem)
    {
        $this->miscItemRepository->persist($miscItem);
    }
}
