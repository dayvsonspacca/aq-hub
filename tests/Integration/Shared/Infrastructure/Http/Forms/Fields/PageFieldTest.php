<?php

declare(strict_types=1);

namespace AqHub\Tests\Integration\Shared\Infrastructure\Http\Forms\Fields;

use AqHub\Shared\Infrastructure\Http\Forms\Fields\PageField;
use AqHub\Tests\Unit\TestRequests;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

final class PageFieldTest extends TestRequests
{
    #[Test]
    public function should_fail_when_page_param_is_zero()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param page cannot be zero or negative.');

        $request = $this->createRequest(['page' => 0]);

        PageField::fromRequest($request);
    }

    #[Test]
    public function should_fail_when_page_param_is_negative()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param page cannot be zero or negative.');

        $request = $this->createRequest(['page' => -5]);

        PageField::fromRequest($request);
    }

    #[Test]
    public function should_fail_when_page_param_is_not_numeric()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Param page needs to be numeric.');

        $request = $this->createRequest(['page' => 'one']);

        PageField::fromRequest($request);
    }
}
