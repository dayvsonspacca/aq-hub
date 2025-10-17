<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\ListAllArmorsForm;
use Symfony\Component\HttpFoundation\Request;

class ArmorController implements RestController
{
    public function __construct(private readonly FindAll $findAll)
    {
    }

    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request)
    {
        $filter = ListAllArmorsForm::fromRequest($request);
        $armors = $this->findAll->execute($filter);
    }
}
