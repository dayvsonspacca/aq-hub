<?php

declare(strict_types=1);

namespace AqWiki\Application\UseCases\Quest\Persist;

use AqWiki\Domain\Repositories\QuestRepositoryInterface;

final class Handler
{
    private QuestRepositoryInterface $questRepository;

    public function __construct(QuestRepositoryInterface $questRepository)
    {
        $this->questRepository = $questRepository;
    }

    public function handle(Request $request)
    {

    }
}
