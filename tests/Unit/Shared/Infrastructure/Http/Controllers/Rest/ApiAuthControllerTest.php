<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Result;
use AqHub\Shared\Infrastructure\Http\Controllers\Rest\ApiAuthController;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;
use AqHub\Shared\Infrastructure\Repositories\Pgsql\PgsqlUsersApiRepository;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthControllerTest extends TestCase
{
    use DoRequests;

    private MockObject&PgsqlUsersApiRepository $repositoryMock;
    private MockObject&JwtAuthService $jtwAuthServiceMock;
    private ApiAuthController $controller;


    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(PgsqlUsersApiRepository::class);
        $this->jtwAuthServiceMock   = $this->createMock(JwtAuthService::class);

        $this->controller = new ApiAuthController(
            $this->jtwAuthServiceMock,
            $this->repositoryMock
        );
    }

    #[Test]
    public function should_create_api_auth_controller()
    {
        $this->assertInstanceOf(ApiAuthController::class, $this->controller);
    }

    #[Test]
    public function should_not_login_when_user_not_found()
    {
        $username = 'Hilise';
        $password = 'password';

        $request = $this->makeRequest(
            method: 'POST',
            uri: '/auth/login',
            content: [
                'username' => $username,
                'password' => $password
            ]
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('exists')
            ->with($username, $password)
            ->willReturn(false);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame('{"message":"User not found."}', $response->getContent());
    }

    #[Test]
    public function should_return_token_when_user_exists()
    {
        $username = 'Hilise';
        $password = 'password';

        $request = $this->makeRequest(
            method: 'POST',
            uri: '/auth/login',
            content: [
                'username' => $username,
                'password' => $password
            ]
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('exists')
            ->with($username, $password)
            ->willReturn(true);

        $token = Result::success(null, 'mega-jwt-token');

        $this->jtwAuthServiceMock
            ->expects($this->once())
            ->method('sign')
            ->with(['username' => $username])
            ->willReturn($token);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('{"token":"mega-jwt-token"}', $response->getContent());
    }


    #[Test]
    public function should_result_erro_when_sign_token()
    {
        $username = 'Hilise';
        $password = 'password';

        $request = $this->makeRequest(
            method: 'POST',
            uri: '/auth/login',
            content: [
                'username' => $username,
                'password' => $password
            ]
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('exists')
            ->with($username, $password)
            ->willReturn(true);

        $token = Result::error('Algorithm not supported', null);

        $this->jtwAuthServiceMock
            ->expects($this->once())
            ->method('sign')
            ->with(['username' => $username])
            ->willReturn($token);

        $response = $this->controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertSame('{"message":"Algorithm not supported"}', $response->getContent());
    }
}
