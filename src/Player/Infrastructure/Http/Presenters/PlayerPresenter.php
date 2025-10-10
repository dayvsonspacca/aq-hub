<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Presenters;

use AqHub\Player\Domain\Repositories\Data\PlayerData;

class PlayerPresenter
{
    /**
     * @param PlayerData[] $playersData
     */
    public static function array(array $playersData)
    {
        return array_map(fn ($playerData) => $playerData->toArray(), $playersData);
    }
}
