<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http\Interfaces;

use Closure;
use Symfony\Component\HttpFoundation\{Request, Response};

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}
