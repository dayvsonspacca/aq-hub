<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Controllers;

use AqHub\Core\Infrastructure\Http\Route;
use Symfony\Component\HttpFoundation\{RedirectResponse, Request};

class HomeController
{
    #[Route('/', methods: ['GET'])]
    public function home(Request $request): RedirectResponse
    {
        return new RedirectResponse('/api-docs.html');
    }
}
