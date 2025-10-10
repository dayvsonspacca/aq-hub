<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Http\Forms;

use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Shared\Domain\ValueObjects\Result;
use Symfony\Component\HttpFoundation\Request;

class AddPlayerForm
{
    /**
     * @return Result<Name>
     */
    public static function fromRequest(Request $request): Result
    {
        $post = $request->toArray() ?? [];
        $name = Name::create($post['name'] ?? '');

        if ($name->isError()) {
            return $name;
        }

        return Result::success(null, $name->getData());
    }
}
