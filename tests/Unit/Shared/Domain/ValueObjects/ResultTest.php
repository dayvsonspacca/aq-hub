<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Tests\Unit\TestCase;
use DomainException;
use PHPUnit\Framework\Attributes\Test;

final class ResultTest extends TestCase
{
    #[Test]
    public function when_result_success(): void
    {
        $data   = ['id' => 1, 'name' => 'Test'];
        $result = Result::success('Operation successful', $data);

        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->isError());
        $this->assertEquals('Operation successful', $result->getMessage());
        $this->assertSame($data, $result->getData());
    }

    #[Test]
    public function when_result_error(): void
    {
        $result = Result::error('Something went wrong', null);

        $this->assertFalse($result->isSuccess());
        $this->assertTrue($result->isError());
        $this->assertEquals('Something went wrong', $result->getMessage());
        $this->assertNull($result->getData());
    }

    #[Test]
    public function throw_domain_excepiton_on_with_error_unwrap(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Something went wrong');
        
        $result = Result::error('Something went wrong', null);
        $result->unwrap();
    }
}
