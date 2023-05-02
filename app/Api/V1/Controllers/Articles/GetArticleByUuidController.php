<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\Models\Articles;
use App\Transformers\ArticleTransformer;
use Dingo\Api\Http\Request;
use OpenApi\Annotations as OA;

class GetArticleByUuidController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/items/uuid/{id}",
     *      operationId="getArticleByUuid",
     *      tags={"Article"},
     *      summary="Get specific article by uuid",
     *      description="Returns article data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Article id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Article",
     *              ),
     *            )
     *          )
     *       ),
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
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        try {
            $article = Articles::uuid($id);
            return $this->response->item($article, new ArticleTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
