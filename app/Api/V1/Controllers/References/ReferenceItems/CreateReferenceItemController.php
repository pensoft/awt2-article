<?php

namespace App\Api\V1\Controllers\References\ReferenceItems;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ReferenceItemRequest as Request;
use App\Models\ReferenceItem;
use App\Transformers\ReferenceItemTransformer;

class CreateReferenceItemController extends BaseController
{
    /**
     * @OA\Post(
     *      path="/references/items",
     *      operationId="createReferenceItem",
     *      tags={"ReferenceItem"},
     *      summary="Create a new Reference Item",
     *      description="Create a new Reference Item",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ReferenceItemRequest")
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
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(Request $request)
    {
        $referenceItem = ReferenceItem::create([
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'user_email' => $request->user()->email,
            'title' => $request->get('title'),
            'data' => $request->get('data'),
            'reference_definition_id' => $request->get('reference_definition_id'),

        ]);

        return $this->response->item($referenceItem, new ReferenceItemTransformer);
    }
}
