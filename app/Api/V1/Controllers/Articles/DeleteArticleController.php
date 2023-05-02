<?php

namespace App\Api\V1\Controllers\Articles;

use App\Api\V1\Controllers\BaseController;
use App\Models\Articles;
use Dingo\Api\Http\Request;
use OpenApi\Annotations as OA;

class DeleteArticleController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/articles/items/{id}",
     *      operationId="deleteArticle",
     *      tags={"Article"},
     *      summary="Delete the article",
     *      description="Delete the article",
     *      security={{"passport":{}}},
     *
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
        Articles::whereId($id)->delete();

        return $this->response->noContent();
    }
}
