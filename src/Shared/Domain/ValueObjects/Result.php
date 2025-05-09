<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\ResultStatus;
use DomainException;

final class Result
{
    private ResultStatus $status;
    private ?string $message;

    /**
     * @var T|null
     */
    private $data;

    /**
     * @param T|null $data
     */
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
     * @return T|null
     * @throws DomainException
     */
    public function unwrap()
    {
        if ($this->isError()) {
            throw new DomainException($this->message);
        }

        return $this->getData();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return T|null
     */
    public function getData()
    {
        return $this->data;
    }
}
