<?php

namespace App\Api\V1\Controllers\ArticleTemplates;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleTemplates;
use App\QueryBuilder\Filters\FiltersExactOrNotExact;
use App\Transformers\ArticleTemplateShortTransformer;
use App\Transformers\ArticleTemplateTransformer;
use Dingo\Api\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GetArticleTemplatesController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/templates",
     *      operationId="getArticleTemplates",
     *      tags={"ArticleTemplate"},
     *      summary="Get all article templates",
     *      description="Returns all article templates",
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
     *                  @OA\Items(ref="#/components/schemas/ArticleTemplate"),
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

        $templates = QueryBuilder::for(ArticleTemplates::class)
            ->defaultSort('id')
            ->allowedSorts('id', 'name')
            ->allowedFilters('name',
                AllowedFilter::custom('id', new FiltersExactOrNotExact),
            )->paginate($pageSize);

        $filters = request()->query();


        return $this->response->paginator($templates, new ArticleTemplateShortTransformer)->setMeta($filters);
    }
}
