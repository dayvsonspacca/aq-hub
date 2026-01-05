<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Shared\Infrastructure\Http\Middlewares;

use AqHub\Core\Env;
use AqHub\Shared\Infrastructure\Http\Middlewares\JwtAuthMiddleware;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;
use AqHub\Tests\TestCase;
use AqHub\Tests\Traits\DoRequests;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

final class JwtAuthMiddlewareTest extends TestCase
{
    use DoRequests;

    private JwtAuthMiddleware $middleware;
    private string $token;

    protected function setUp(): void
    {
        $jwtAuthService = new JwtAuthService(Env::load(['API_JWT_SECRET_TOKEN' => 'SUPER_SECRET_WAS_TOO_LOW_SO_I_INCRESEAD_A_BIT'], forceReload: true));
        $this->middleware = new JwtAuthMiddleware($jwtAuthService);

        $this->token = $jwtAuthService->sign(['username' => 'Hilise']);
    }

    #[Test]
    public function should_return_response_unauthorized_when_bearer_auth_not_present()
    {
        $request = $this->makeRequest();
        $response = $this->middleware->handle($request, fn() => new Response());

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame('Unauthorized', $response->getContent());
    }

    #[Test]
    public function should_return_response_unauthorized_by_invalid_or_expired_token()
    {
        $request = $this->makeRequest();
        $request->headers->set('Authorization', 'Bearer invalidtoken');
        $response = $this->middleware->handle($request, fn() => new Response());

        $this->assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame('Invalid or expired token.', $response->getContent());
    }


    #[Test]
    public function should_pass_middleware_when_valid_token()
    {
        $request = $this->makeRequest();
        $request->headers->set('Authorization', 'Bearer ' . $this->token);
        $response = $this->middleware->handle($request, fn() => new Response(
            content: 'You shall pass'
        ));

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('You shall pass', $response->getContent());
        
        $this->assertTrue($request->attributes->has('auth_user'));
        $this->assertSame($request->attributes->get('auth_user')['username'], 'Hilise');
    }
}
