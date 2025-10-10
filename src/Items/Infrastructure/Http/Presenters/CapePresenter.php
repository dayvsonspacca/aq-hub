<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Presenters;

use AqHub\Items\Domain\Repositories\Data\CapeData;

class CapePresenter
{
    /**
     * @param CapeData[] $capesData
     */
    public static function array(array $capesData)
    {
        return array_map(fn ($capeData) => $capeData->toArray(), $capesData);
    }
}
