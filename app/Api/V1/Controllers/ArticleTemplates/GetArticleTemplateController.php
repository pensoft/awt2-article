<?php

namespace App\Api\V1\Controllers\ArticleTemplates;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleTemplates;
use App\Transformers\ArticleTemplateTransformer;
use Dingo\Api\Http\Request;

class GetArticleTemplateController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/articles/templates/{id}",
     *      operationId="getArticleTemplateById",
     *      tags={"ArticleTemplate"},
     *      summary="Get specific article template",
     *      description="Returns article template data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Article template id",
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
     *                  ref="#/components/schemas/ArticleTemplate",
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
            $user = ArticleTemplates::findOrFail($id);
            return $this->response->item($user, new ArticleTemplateTransformer);
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
