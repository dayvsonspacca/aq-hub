<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use Symfony\Component\HttpFoundation\Request;

class ArmorController implements RestController
{
    public function __construct() {}

    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request)
    {
    }
}
