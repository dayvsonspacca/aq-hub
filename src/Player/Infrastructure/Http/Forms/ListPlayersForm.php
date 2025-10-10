<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Forms;

use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Shared\Domain\ValueObjects\Result;
use Symfony\Component\HttpFoundation\Request;

class ListPlayersForm
{
    /**
     * @return Result<PlayerFilter>
     */
    public static function fromRequest(Request $request): Result
    {
        $page = (int) $request->get('page', 1);

        if ($page <= 0) {
            return Result::error('Param page cannot be zero or negative.', null);
        }

        $filter = new PlayerFilter();
        $filter->setPage($page);

        $mined = mb_strtolower($request->get('mined', ''));
        if ($mined === 'yes' || $mined === 'no') {
            $filter->mined = $mined === 'yes' ? true : false;
        }

        return Result::success(null, $filter);
    }
}
