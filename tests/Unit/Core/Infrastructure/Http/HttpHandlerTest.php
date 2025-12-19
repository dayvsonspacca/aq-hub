<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\ContainerFactory;
use AqHub\Core\CoreDefinitions;
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
        $this->container = ContainerFactory::make([
            CoreDefinitions::dependencies(),
            HttpDefinitions::dependencies(),
            [
                'Controllers.Rest' => add([RestControllerStub::class])
            ]
        ]);
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
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    #[Test]
    public function should_result_in_not_found_when_no_endpoint()
    {
        $httpHandler = $this->container->get(HttpHandler::class);
        $request     = $this->makeRequest(
            uri: '/api/this-endpoint-will-never-exists'
        );

        /** @var Response $response */
        $response = $httpHandler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    #[Test]
    public function should_result_in_internal_server_error_when_error()
    {
        $httpHandler = $this->container->get(HttpHandler::class);
        $request     = $this->makeRequest(
            uri: '/api/error'
        );

        /** @var Response $response */
        $response = $httpHandler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    #[Test]
    public function should_add_cors_headers()
    {
        $httpHandler = $this->container->get(HttpHandler::class);
        $request     = $this->makeRequest(
            uri: '/api/list'
        );

        /** @var Response $response */
        $response = $httpHandler->handle($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertSame('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertSame('GET', $response->headers->get('Access-Control-Allow-Methods'));
        $this->assertSame('Content-Type, Authorization', $response->headers->get('Access-Control-Allow-Headers'));
    }
}
