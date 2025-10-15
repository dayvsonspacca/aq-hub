<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

final class HttpHandler
{
    public function __construct(private readonly array $controllers)
    {
    }

    public function handle()
    {
        $request = Request::createFromGlobals();
        $context = new RequestContext();
        $context->fromRequest($request);
    }
}