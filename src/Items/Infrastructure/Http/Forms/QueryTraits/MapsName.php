<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\QueryTraits;

use AqHub\Items\Domain\ValueObjects\Name;
use Symfony\Component\HttpFoundation\Request;

trait MapsName
{
    private static function mapName(Request $request): ?Name
    {
        $nameString = $request->query->get('name');

        if (empty($nameString)) {
            return null;
        }

        $name = Name::create($nameString);
        if ($name->isError()) {
            return null;
        }

        return $name->getData();
    }
}
