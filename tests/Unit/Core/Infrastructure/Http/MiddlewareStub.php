<?php

declare(strict_types=1);

namespace AqHub\Tests\Unit\Core\Infrastructure\Http;

use AqHub\Core\Infrastructure\Http\Interfaces\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareStub implements Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->add(['pass' => true]);
        
        return $next($request);
    }
}