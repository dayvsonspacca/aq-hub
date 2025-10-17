<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Infrastructure\Http\Forms\Traits\{MapsName, MapsPagination, MapsRarities, MapsTags};
use Symfony\Component\HttpFoundation\Request;

class ListAllArmorsForm
{
    use MapsPagination;
    use MapsName;
    use MapsRarities;
    use MapsTags;

    public static function fromRequest(Request $request): ArmorFilter
    {
        $filter = new ArmorFilter();

        $pagination = self::mapPagination($request);
        $filter->setPage($pagination['page']);
        $filter->setPageSize($pagination['page_size']);

        $filter->setName(self::mapName($request));
        $filter->setRarities(self::mapRarities($request));
        $filter->setTags(self::mapTags($request));

        return $filter;
    }
}
