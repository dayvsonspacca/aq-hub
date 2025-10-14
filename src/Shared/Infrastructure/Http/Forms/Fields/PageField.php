<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Forms\Fields;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class PageField
{
    public static function fromRequest(Request $request): int
    {
        $page = $request->get('page', 1);

        if (!is_numeric($page)) {
            throw new InvalidArgumentException('Param page needs to be numeric.');
        }

        $page = (int) $page;

        if ($page <= 0) {
            throw new InvalidArgumentException('Param page cannot be zero or negative.');
        }

        return $page;
    }
}
