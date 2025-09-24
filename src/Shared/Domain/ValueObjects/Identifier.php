<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\ValueObjects;

/**
 * Represents an identifier based on a positive integer value.
 *
 * This class ensures, through its static factory method,
 * that an identifier can only be created if the provided value
 * is greater than zero. This prevents the creation of invalid identifiers.
 *
 * Example usage:
 * ```php
 * $result = Identifier::create(10);
 * if ($result->isSuccess()) {
 *     $id = $result->getValue();
 *     echo $id->getValue(); // 10
 * }
 * ```
 */
class Identifier
{
    /**
     * Private constructor to enforce controlled instantiation
     * via the static `create()` method.
     *
     * @param int $value Positive integer that represents the identifier.
     */
    private function __construct(
        private readonly int $value
    ) {
    }

    /**
     * Creates a new Identifier instance while validating business rules.
     *
     * @param int $value Integer value to be used as an identifier.
     *
     * @return Result<Identifier> Returns a Result object containing:
     *   - success: the valid Identifier instance;
     *   - error: a descriptive message if the value is invalid.
     */
    public static function create(int $value)
    {
        if ($value <= 0) {
            return Result::error(
                'An identifier must be greater than zero.',
                null
            );
        }

        return Result::success(null, new self($value));
    }

    /**
     * Gets the integer value of the identifier.
     *
     * @return int Positive integer value of the identifier.
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
