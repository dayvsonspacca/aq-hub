<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http\Interfaces;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface Middleware
{
    public function handle(Request $request, Closure $next): Response;
}