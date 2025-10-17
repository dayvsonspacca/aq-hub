<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Traits;

use Symfony\Component\HttpFoundation\Request;

trait MapsPagination
{
    /**
     * @return array{page: int, page_size: int}
     */
    private static function mapPagination(Request $request): array
    {
        $page     = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('page_size', 20);

        if ($pageSize > 100) {
            $pageSize = 100;
        }
        if ($page < 1) {
            $page = 1;
        }

        return [
            'page' => $page,
            'page_size' => $pageSize,
        ];
    }
}
