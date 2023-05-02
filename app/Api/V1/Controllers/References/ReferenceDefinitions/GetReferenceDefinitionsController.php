<?php
namespace App\Api\V1\Controllers\References\ReferenceDefinitions;

use App\Api\V1\Controllers\BaseController;
use App\Models\ReferenceDefinition;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\ReferenceDefinitionTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetReferenceDefinitionsController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/references/definitions",
     *      operationId="getReferenceDefinitions",
     *      tags={"ReferenceDefinition"},
     *      summary="Get all Reference Definitions",
     *      description="Returns all Reference Definitions",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="page",
     *         example=1,
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="pageSize",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="Page size",
     *         example=10,
     *         required=false,
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/ReferenceDefinition"),
     *              ),
     *            )
     *          )
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */

    public function __invoke(Request $request)
    {
        $pageSize = $request->get('pageSize', 10);
        $referenceDefinition = QueryBuilder::for(ReferenceDefinition::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'title')
            ->allowedFilters('title',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();
        return $this->response->paginator($referenceDefinition, new ReferenceDefinitionTransformer)->setMeta($filters);
    }
}
