<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\Enums\ResultStatus;

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
