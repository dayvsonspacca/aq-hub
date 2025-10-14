<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Domain\ValueObjects\Name;
use Symfony\Component\HttpFoundation\Request;
use InvalidArgumentException;

class NameField
{
    /**
     * @return Name|null
     */
    public static function fromRequest(Request $request): ?Name
    {
        $name = $request->get('name', false);

        if (!$name) {
            return null;
        }

        $result = Name::create($name);

        if ($result->isError()) {
            throw new InvalidArgumentException($result->getMessage());
        }

        return $result->getData();
    }
}