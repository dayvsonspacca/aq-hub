<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Application\Armors\Queries\Outputs\FindAllOutput;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Infrastructure\Http\Controllers\Rest\ArmorController;
use AqHub\Tests\DataProviders\ArmorDataProvider;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};

class ArmorControllerTest extends TestCase
{
    use DoRequests;

    private MockObject&FindAll $findAllQueryMock;
    private ArmorController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->findAllQueryMock = $this->createMock(FindAll::class);

        $this->controller = new ArmorController(
            $this->findAllQueryMock
        );
    }

    #[Test]
    public function should_create_armor_controller()
    {
        $this->assertInstanceOf(ArmorController::class, $this->controller);
    }

    #[Test]
    public function should_list_armors_by_executing_query_and_return_ok_response()
    {
        $mockArmors = ArmorDataProvider::make()->buildCollection(2);

        $expectedJsonResponseData = [
            'filter' => [
                'page' => 1,
                'page_size' => 20,
                'rarities' => [],
                'tags' => [],
                'name' => null
            ],
            'armors' => [
                $mockArmors[0]->toArray(),
                $mockArmors[1]->toArray(),
            ],
            'total' => count($mockArmors)
        ];

        $output = new FindAllOutput($mockArmors, count($mockArmors));

        $this->findAllQueryMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(ArmorFilter::class))
            ->willReturn($output);

        $request  = $this->makeRequest(method: 'GET', uri: '/armors/list?page=1');
        $response = $this->controller->list($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $decodedContent = json_decode($response->getContent(), true);

        $this->assertIsArray($decodedContent);
        $this->assertEquals($expectedJsonResponseData, $decodedContent);
    }
}
