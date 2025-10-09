<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers;

use AqHub\Items\Application\UseCases\Armor\ArmorUseCases;
use AqHub\Items\Infrastructure\Http\Forms\ArmorFilterForm;
use AqHub\Items\Infrastructure\Http\Presenters\ArmorPresenter;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use AqHub\Shared\Infrastructure\Http\Route;
use RuntimeException;

class ArmorController
{
    public function __construct(
        private readonly ArmorUseCases $armorUseCases
    ) {}

    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $result = ArmorFilterForm::fromRequest($request);
        if ($result->isError()) {
            return new JsonResponse(['message' => $result->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filter = $result->getData();
        $armors = $this->armorUseCases->findAll->execute($filter);

        if ($armors->isError()) {
            throw new RuntimeException($armors->getMessage());
        }

        $armors = $armors->getData();
        $armors = ArmorPresenter::array($armors);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'armors' => $armors
        ], Response::HTTP_OK);
    }
}
