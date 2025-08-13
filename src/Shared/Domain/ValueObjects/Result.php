<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\ResultStatus;
use DomainException;

/**
 * @template T
 */
final class Result
{
    private ResultStatus $status;
    private ?string $message;

    /** @var T */
    private $data;

    /** @param T $data */
    public function __construct(
        ResultStatus $status,
        ?string $message,
        $data
    ) {
        $this->status  = $status;
        $this->message = $message;
        $this->data    = $data;
    }

    public function isSuccess(): bool
    {
        return $this->status === ResultStatus::Success;
    }

    public function isError(): bool
    {
        return $this->status === ResultStatus::Error;
    }

    /**
     * @return T
     * @throws DomainException
     */
    public function unwrap()
    {
        if ($this->isError()) {
            throw new DomainException($this->message);
        }

        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return T
     */
    public function getData()
    {
        return $this->data;
    }

    public static function success(?string $message, $data)
    {
        return new self(ResultStatus::Success, $message, $data);
    }

    public static function error(?string $message, $data)
    {
        return new self(ResultStatus::Error, $message, $data);
    }
}
