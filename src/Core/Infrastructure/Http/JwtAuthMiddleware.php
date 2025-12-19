<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use AqHub\Core\Env;
use AqHub\Core\Infrastructure\Http\Interfaces\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use RuntimeException;

class JwtAuthMiddleware implements Middleware
{
    public function __construct(
        private Env $env
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!isset($this->env->vars['API_JWT_SECRET_TOKEN'])) {
            throw new RuntimeException('API_JWT_SECRET_TOKEN not set.');
        }

        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key($this->env->vars['API_JWT_SECRET_TOKEN'], 'HS256'));

            $request->attributes->set('auth_user', (array) $decoded);

            return $next($request);
        } catch (Exception $e) {
            return new Response('Invalid or expired token.', Response::HTTP_UNAUTHORIZED);
        }
    }
}
