<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\ContainerFactory;
use AqHub\Core\Infrastructure\Http\{HttpDefinitions, HttpHandler};
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;

use function DI\add;

use DI\Container;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

final class HttpHandlerTest extends TestCase
{
    use DoRequests;

    private Container $container;

    protected function setUp(): void
    {
        $this->container = ContainerFactory::make(array_merge(
            HttpDefinitions::dependencies(),
            [
                'Controllers.Rest' => add([RestControllerStub::class])
            ]
        ));
    }

    #[Test]
    public function should_handle_http_request()
    {
        $httpHandler = $this->container->get(HttpHandler::class);
        $request     = $this->makeRequest(
            uri: '/api/list'
        );

        /** @var Response $response */
        $response = $httpHandler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }
}
