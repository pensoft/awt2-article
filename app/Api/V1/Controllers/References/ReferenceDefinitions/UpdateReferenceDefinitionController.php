<?php

namespace App\Api\V1\Controllers\References\ReferenceDefinitions;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\UpdateArticleSectionRequest;
use App\Api\V1\Requests\ReferenceDefinitionRequest;
use App\Models\ReferenceDefinition;
use App\Traits\VersionsTrait;
use App\Transformers\ReferenceDefinitionTransformer;

class UpdateReferenceDefinitionController extends BaseController
{
    use VersionsTrait;

    /**
     * @OA\Put(
     *      path="/references/definitions/{id}",
     *      operationId="updateReferenceDefinition",
     *      tags={"ReferenceDefinition"},
     *      summary="Update the Reference Definition",
     *      description="Update the Reference Definition",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ReferenceDefinition id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ReferenceDefinitionRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ReferenceDefinition",
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
     * @param ReferenceDefinitionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(ReferenceDefinitionRequest $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);

        try {
            $referenceDefinition = ReferenceDefinition::findOrFail($id);

            $referenceDefinition->fill([
                'title'=>$request->get('title'),
                'type'=>$request->get('type'),
                'schema'=>$request->get('schema'),
                'settings' => $request->get('settings'),
                'user_id' => $request->user()->id,
                'user_name' => $request->user()->name,
                'user_email' => $request->user()->email,
            ])->save();


            return $this->response->item($referenceDefinition, new ReferenceDefinitionTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
