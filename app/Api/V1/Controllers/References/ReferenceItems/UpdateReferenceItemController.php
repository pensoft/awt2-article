<?php

namespace App\Api\V1\Controllers\References\ReferenceItems;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\UpdateArticleSectionRequest;
use App\Api\V1\Requests\ReferenceItemRequest;
use App\Models\ReferenceItem;
use App\Traits\VersionsTrait;
use App\Transformers\ReferenceItemTransformer;

class UpdateReferenceItemController extends BaseController
{
    use VersionsTrait;

    /**
     * @OA\Put(
     *      path="/references/items/{id}",
     *      operationId="updateReferenceItem",
     *      tags={"ReferenceItem"},
     *      summary="Update the Reference Item",
     *      description="Update the Reference Item",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReferenceItem id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *
     * @param ReferenceItemRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(ReferenceItemRequest $request, $id)
    {

        try {
            $referenceItem = ReferenceItem::where('id', $id)->orWhere('uuid', $id)->first();

            $referenceItem->fill([
                'title'=>$request->get('title'),
                'data'=>$request->get('data'),
                'reference_definition_id' => $request->get('reference_definition_id'),
                'user_id' => $request->user()->id,
                'user_name' => $request->user()->name,
                'user_email' => $request->user()->email,
            ])->save();


            return $this->response->item($referenceItem, new ReferenceItemTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
