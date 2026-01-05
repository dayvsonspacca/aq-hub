<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Middlewares;

use AqHub\Core\Infrastructure\Http\Interfaces\Middleware;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;
use Closure;
use Symfony\Component\HttpFoundation\{Request, Response};

class JwtAuthMiddleware implements Middleware
{
    public function __construct(
        private JwtAuthService $jwtService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $token    = str_replace('Bearer ', '', $authHeader);
        $result   = $this->jwtService->validate($token);

        if ($result->isError()) {
            return new Response('Invalid or expired token.', Response::HTTP_UNAUTHORIZED);
        }

        $userData = $result->getData();
        $request->attributes->set('auth_user', $userData);

        return $next($request);
    }
}
