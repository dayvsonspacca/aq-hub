<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Forms;

use AqHub\Items\Infrastructure\Http\Forms\ListAllCapesForm;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;

final class ListAllCapesFormTest extends TestCase
{
    use DoRequests;

    #[Test]
    public function should_map_request_to_cape_filter_with_all_fields()
    {
        $request = $this->makeRequest(
            method: 'GET',
            uri: '/capes',
            query: [
                'name' => 'Lich Cape',
                'page' => 2,
                'page_size' => 15,
                'rarities' => 'Epic,Legendary',
                'tags' => 'ac,legend',
                'can_access_bank' => 'Y'
            ]
        );

        $filter = ListAllCapesForm::fromRequest($request);

        $this->assertInstanceOf(CapeFilter::class, $filter);
        $this->assertSame('Lich Cape', $filter->name->value);
        $this->assertSame(2, $filter->page);
        $this->assertSame(15, $filter->pageSize);
        $this->assertTrue($filter->canAccessBank);
        
        $this->assertCount(2, $filter->rarities);
        $this->assertCount(2, $filter->tags);
    }

    #[Test]
    public function should_handle_can_access_bank_boolean_mapping()
    {
        $requestNo = $this->makeRequest(method: 'GET', uri: '/capes', query: ['can_access_bank' => 'n']);
        $filterNo = ListAllCapesForm::fromRequest($requestNo);
        $this->assertFalse($filterNo->canAccessBank);

        $requestInvalid = $this->makeRequest(method: 'GET', uri: '/capes', query: ['can_access_bank' => 'maybe']);
        $filterInvalid = ListAllCapesForm::fromRequest($requestInvalid);
        $this->assertNull($filterInvalid->canAccessBank);
    }
}