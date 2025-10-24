<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Controllers\Forms;

use AqHub\Items\Domain\ValueObjects\Name;
use AqHub\Items\Infrastructure\Http\Forms\ListAllArmorsForm;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;

final class DefaultFormTraitsTest extends TestCase
{
    use DoRequests;

    #[Test]
    public function should_convert_negative_page_to_one()
    {
        $request = $this->makeRequest(
            query: ['page' => -1]
        );

        $filter = ListAllArmorsForm::fromRequest($request);

        $this->assertSame(1, $filter->page);
    }

    #[Test]
    public function should_limit_page_size_to_100()
    {
        $request = $this->makeRequest(
            query: ['page_size' => 1000]
        );

        $filter = ListAllArmorsForm::fromRequest($request);

        $this->assertSame(100, $filter->pageSize);
    }

    #[Test]
    public function should_not_filter_by_name_when_name_empty()
    {
        $request = $this->makeRequest(
            query: ['name' => '']
        );

        $filter = ListAllArmorsForm::fromRequest($request);

        $this->assertNull($filter->name);
    }

    #[Test]
    public function should_not_filter_by_name_when_name_invalid()
    {
        $request = $this->makeRequest(
            query: ['name' => '  ']
        );

        $filter = ListAllArmorsForm::fromRequest($request);

        $this->assertNull($filter->name);
    }

    #[Test]
    public function should_filter_by_name_when_valid()
    {
        $request = $this->makeRequest(
            query: ['name' => 'Archfiend Doomlord']
        );

        $filter = ListAllArmorsForm::fromRequest($request);

        $this->assertInstanceOf(Name::class, $filter->name);
        $this->assertSame('Archfiend Doomlord', $filter->name->value);
    }
}
