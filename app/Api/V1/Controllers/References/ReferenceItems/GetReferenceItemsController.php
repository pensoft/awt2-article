<?php
namespace App\Api\V1\Controllers\References\ReferenceItems;

use App\Api\V1\Controllers\BaseController;
use App\Models\ReferenceItem;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\ReferenceItemTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetReferenceItemsController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/references/items",
     *      operationId="getReferenceItems",
     *      tags={"ReferenceItem"},
     *      summary="Get all Reference Items",
     *      description="Returns all Reference Items",
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
     *                  @OA\Items(ref="#/components/schemas/ReferenceItem"),
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
        $referenceItem = QueryBuilder::for(ReferenceItem::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'title')
            ->allowedFilters('title',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
                AllowedFilter::custom('uuid', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();
        return $this->response->paginator($referenceItem, new ReferenceItemTransformer)->setMeta($filters);
    }
}
