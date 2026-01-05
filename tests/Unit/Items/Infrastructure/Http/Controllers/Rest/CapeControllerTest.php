<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Items\Application\Capes\Queries\FindAll;
use AqHub\Items\Application\Capes\Queries\Outputs\FindAllOutput;
use AqHub\Items\Domain\Repositories\Filters\{CapeFilter};
use AqHub\Items\Infrastructure\Http\Controllers\Rest\CapeController;
use AqHub\Tests\DataProviders\{CapeDataProvider};
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};

class CapeControllerTest extends TestCase
{
    use DoRequests;

    private MockObject&FindAll $findAllQueryMock;
    private CapeController $controller;


    protected function setUp(): void
    {
        parent::setUp();

        $this->findAllQueryMock = $this->createMock(FindAll::class);

        $this->controller = new CapeController(
            $this->findAllQueryMock
        );
    }

    #[Test]
    public function should_create_armor_controller()
    {
        $this->assertInstanceOf(CapeController::class, $this->controller);
    }

    #[Test]
    public function should_list_capes_by_executing_query_and_return_ok_response()
    {
        $mockCapes = CapeDataProvider::make()->buildCollection(2);

        $expectedJsonResponseData = [
            'filter' => [
                'page' => 1,
                'page_size' => 20,
                'rarities' => [],
                'tags' => [],
                'name' => null,
                'can_access_bank' => null
            ],
            'capes' => [
                $mockCapes[0]->toArray(),
                $mockCapes[1]->toArray(),
            ],
            'total' => count($mockCapes)
        ];

        $output = new FindAllOutput($mockCapes, count($mockCapes));

        $this->findAllQueryMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(CapeFilter::class))
            ->willReturn($output);

        $request  = $this->makeRequest(method: 'GET', uri: '/capes/list?page=1');
        $response = $this->controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $decodedContent = json_decode($response->getContent(), true);

        $this->assertIsArray($decodedContent);
        $this->assertEquals($expectedJsonResponseData, $decodedContent);
    }
}
