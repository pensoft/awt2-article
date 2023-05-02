<?php

namespace App\Api\V1\Controllers\ArticleTemplates;

use App\Api\V1\Controllers\BaseController;
use App\Models\ArticleTemplates;
use Dingo\Api\Http\Request;

class DeleteArticleTemplateController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/articles/templates/{id}",
     *      operationId="deleteArticleTemplate",
     *      tags={"ArticleTemplate"},
     *      summary="Delete the article template",
     *      description="Delete the article template",
     *      security={{"passport":{}}},
     *
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
     *          response=204,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *          )
     *       ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     *
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request, $id)
    {
        ArticleTemplates::whereId($id)->delete();

        return $this->response->noContent();
    }
}
