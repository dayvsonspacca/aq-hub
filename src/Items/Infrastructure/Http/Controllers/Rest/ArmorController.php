<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Controllers\Rest;

use AqHub\Core\Infrastructure\Http\Interfaces\RestController;
use AqHub\Core\Infrastructure\Http\Route;
use AqHub\Items\Application\Armors\Queries\FindAll;
use AqHub\Items\Infrastructure\Http\Forms\ListAllArmorsForm;
use AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters\{NameParameter, RaritiesParameter, TagsParamenter};
use AqHub\Items\Infrastructure\Http\OpenAPI\Response\ListArmorsResponse;
use AqHub\Shared\Domain\Helpers\ArrayPresenter;
use AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters\{PageParameter, PageSizeParameter};
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class ArmorController implements RestController
{
    public function __construct(private readonly FindAll $findAll)
    {
    }

    #[OA\Get(
        path: '/armors/list',
        summary: 'List armors',
        tags: ['Armors'],
        parameters: [new PageParameter(), new PageSizeParameter(), new NameParameter(), new RaritiesParameter(), new TagsParamenter()],
        responses: [new ListArmorsResponse()]
    )]
    #[Route(path: '/armors/list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $filter = ListAllArmorsForm::fromRequest($request);
        $armors = $this->findAll->execute($filter);

        return new JsonResponse([
            'filter' => $filter->toArray(),
            'armors' => ArrayPresenter::presentCollection($armors)
        ], Response::HTTP_OK);
    }
}
