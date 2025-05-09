<?php

declare(strict_types=1);

namespace AqWiki\Item\Domain\Enums;

enum ItemRarity
{
    case WeirdRarity;
    case RareRarity;
    case EpicRarity;
    case LegendaryItemRarity;
}
