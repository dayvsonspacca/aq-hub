<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Domain\ValueObjects\Name;
use Symfony\Component\HttpFoundation\Request;
use InvalidArgumentException;

class NameField
{
    public static function fromRequest(Request $request): Name
    {
        $name = $request->get('name', '');
        
        $name = Name::create($name);

        if ($name->isError()) {
            throw new InvalidArgumentException($name->getMessage());
        }

        return $name->getData();
    }
}