<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\ValueObjects;

use AqHub\Core\Result;
use AqHub\Shared\Domain\Contracts\Identifier;

/**
 * Represents an identifier based on a string value.
 *
 * This class ensures, through its static factory method,
 * that an identifier can only be created if the provided string
 * is not empty. This prevents the creation of invalid identifiers.
 *
 * Example usage:
 * ```php
 * $result = StringIdentifier::create('abc123');
 * if ($result->isSuccess()) {
 *     $id = $result->getValue();
 *     echo $id->getValue(); // 'abc123'
 * }
 * ```
 */
class StringIdentifier implements Identifier
{
    /**
     * Private constructor to enforce controlled instantiation
     * via the static `create()` method.
     *
     * @param string $value Non-empty string that represents the identifier.
     */
    private function __construct(
        private readonly string $value
    ) {
    }

    /**
     * Creates a new StringIdentifier instance while validating business rules.
     *
     * @param string $value String value to be used as an identifier.
     *
     * @return Result<StringIdentifier> Returns a Result object containing:
     *   - success: the valid StringIdentifier instance;
     *   - error: a descriptive message if the value is invalid.
     */
    public static function create(string $value)
    {
        if (trim($value) === '') {
            return Result::error(
                'A string identifier cannot be empty.',
                null
            );
        }

        return Result::success(null, new self($value));
    }

    /**
     * Gets the string value of the identifier.
     *
     * @return string Non-empty string value of the identifier.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
