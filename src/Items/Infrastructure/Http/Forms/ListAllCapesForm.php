<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Infrastructure\Http\Forms\Traits\{MapsName, MapsPagination, MapsRarities, MapsTags};
use Symfony\Component\HttpFoundation\Request;

class ListAllCapesForm
{
    use MapsPagination;
    use MapsName;
    use MapsRarities;
    use MapsTags;

    public static function fromRequest(Request $request): CapeFilter
    {
        $filter = new CapeFilter();

        $pagination = self::mapPagination($request);
        $filter->setPage($pagination['page']);
        $filter->setPageSize($pagination['page_size']);

        $filter->setName(self::mapName($request));
        $filter->setRarities(self::mapRarities($request));
        $filter->setTags(self::mapTags($request));

        $canAccessBank = mb_strtoupper($request->query->get('can_access_bank', ''));
        $canAccessBank = match ($canAccessBank) {
            'Y' => true,
            'N' => false,
            default => null
        };

        if (!is_null($canAccessBank)) {
            $filter->setCanAccessBank($canAccessBank);
        }

        return $filter;
    }
}
