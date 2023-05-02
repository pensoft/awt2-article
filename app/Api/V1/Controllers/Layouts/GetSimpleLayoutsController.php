<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Api\V1\Controllers\BaseController;
use App\Models\Layout;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\LayoutTransformer;
use App\Transformers\SimpleLayoutTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetSimpleLayoutsController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/layouts/simple",
     *      operationId="getSimpleLayouts",
     *      tags={"Layout"},
     *      summary="Get all layouts simple schema",
     *      description="Returns all layouts simple schema",
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
     *     @OA\Parameter(
     *         name="filters[]",
     *         in="query",
     *         description="filters",
     *         required=false,
     *         @OA\Schema(
     *              type="array",
     *              @OA\Items(type="enum", enum={"id","name","article_template_id"}),
     *              example={1,2}
     *          )
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
     *                  @OA\Items(ref="#/components/schemas/SimpleLayout"),
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
    public function __invoke(Request $request){

        $pageSize = $request->get('pageSize', 10);

        $layout = QueryBuilder::for(Layout::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'name', 'article_template_id')
            ->allowedFilters('name',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
                AllowedFilter::custom('article_template_id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();


        return $this->response->paginator($layout, new SimpleLayoutTransformer)->setMeta($filters);
    }
}
