<?php

declare(strict_types=1);

namespace AqWiki\Domain\Exceptions;

use AqWiki\Domain\Enums;
use Exception;

final class AqwItemException extends Exception
{
    public static function duplicateItemTag(Enums\TagType $tag): self
    {
        return new self('The tag `'. $tag->name .'` is already in the item tags.');
    }

    public static function invalidAttribute(string $message): self
    {
        return new self($message);
    }
}
