<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Shared\Domain\Enums\ResultStatus;
use PHPUnit\Framework\Attributes\Test;
use AqWiki\Tests\Unit\TestCase;

final class ResultTest extends TestCase
{
    #[Test]
    public function test_result_success(): void
    {
        $data   = ['id' => 1, 'name' => 'Test'];
        $result = Result::success('Operation successful', $data);

        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->isError());
        $this->assertEquals('Operation successful', $result->getMessage());
        $this->assertSame($data, $result->getData());
    }

    #[Test]
    public function test_result_error(): void
    {
        $result = Result::error('Something went wrong', null);

        $this->assertFalse($result->isSuccess());
        $this->assertTrue($result->isError());
        $this->assertEquals('Something went wrong', $result->getMessage());
        $this->assertNull($result->getData());
    }
}
