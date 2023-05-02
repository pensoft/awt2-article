<?php

namespace App\Api\V1\Controllers\References\ReferenceItems;

use App\Api\V1\Controllers\BaseController;
use App\Models\ReferenceItem;
use App\Transformers\ReferenceItemTransformer;
use Dingo\Api\Http\Request;

class GetReferenceItemController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/references/items/{id}",
     *      operationId="getReferenceItemById",
     *      tags={"ReferenceItem"},
     *      summary="Get Reference Item",
     *      description="Returns Reference Item data",
     *      security={{"passport":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Reference Item id",
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
     *                  ref="#/components/schemas/ReferenceItem",
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
            $item = ReferenceItem::where('id', $id)->orWhere('uuid', $id)->first();
            return $this->response->item($item, new ReferenceItemTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
