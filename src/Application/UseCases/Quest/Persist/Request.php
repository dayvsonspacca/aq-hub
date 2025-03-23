<?php

declare(strict_types=1);

namespace AqWiki\Application\UseCases\Quest\Persist;

use AqWiki\Domain\Entities\Quest;
use AqWiki\Domain\ValueObjects;

final class Request
{
    public function __construct(public readonly Quest $quest)
    {
    }
}
