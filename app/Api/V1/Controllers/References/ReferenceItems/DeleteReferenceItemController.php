<?php

namespace App\Api\V1\Controllers\References\ReferenceItems;

use App\Api\V1\Controllers\BaseController;
use App\Models\ReferenceItem;
use Dingo\Api\Http\Request;

class DeleteReferenceItemController extends BaseController
{
    /**
     * @OA\Delete (
     *      path="/references/items/{id}",
     *      operationId="deleteReferenceItem",
     *      tags={"ReferenceItem"},
     *      summary="Delete the Reference Item",
     *      description="Delete the Reference Item",
     *      security={{"passport":{}}},
     *
     *     @OA\Parameter(
     *          name="id",
     *          description="ReferenceItem id",
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
        ReferenceItem::whereId($id)->orWhere('uuid', $id)->delete();

        return $this->response->noContent();
    }
}
