<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms;

use AqHub\Items\Infrastructure\Http\Forms\FindCapesForm;
use AqHub\Tests\Unit\TestRequests;
use PHPUnit\Framework\Attributes\Test;

final class FindCapesFormTest extends TestRequests
{
    #[Test]
    public function should_create_default_filter_if_request_empty()
    {
        $request = $this->createRequest();

        $result = FindCapesForm::fromRequest($request);
        $this->assertTrue($result->isSuccess());

        $filter = $result->getData();

        $this->assertSame($filter->page, 1);
        $this->assertSame($filter->pageSize, 25);
        $this->assertSame($filter->rarities, []);
        $this->assertSame($filter->tags, []);
        $this->assertSame($filter->name, null);
    }

    #[Test]
    public function should_fail_if_pass_name_but_invalid()
    {
        $request = $this->createRequest(['name' => '   ']);

        $result = FindCapesForm::fromRequest($request);

        $this->assertTrue($result->isError());
    }

    #[Test]
    public function should_pass_when_name_is_valid()
    {
        $request = $this->createRequest(['name' => 'Cape of Awe']);

        $result = FindCapesForm::fromRequest($request);

        $this->assertTrue($result->isSuccess());
        $this->assertSame($result->getData()->name->value, 'Cape of Awe');
    }

    #[Test]
    public function should_pass_when_can_acces_bank_is_valid()
    {
        $request = $this->createRequest(['can_access_bank' => 'yes']);

        $result = FindCapesForm::fromRequest($request);

        $this->assertTrue($result->isSuccess());
        $this->assertSame($result->getData()->canAccessBank, true);
    }
}
