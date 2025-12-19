<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuthMiddleware implements Middleware
{
    public function __construct(
        private string $secretKey
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            $request->attributes->set('auth_user', (array) $decoded);

            return $next($request);
        } catch (Exception $e) {
            return new Response('Invalid or expired token.', Response::HTTP_UNAUTHORIZED);
        }
    }
}
