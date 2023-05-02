<?php

namespace App\Api\V1\Controllers\References\ReferenceDefinitions;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ReferenceDefinitionRequest;
use App\Models\ReferenceDefinition;
use App\Traits\ArticleSectionsTrait;
use App\Transformers\ReferenceDefinitionTransformer;

class CreateReferenceDefinitionController extends BaseController
{

    use ArticleSectionsTrait;
    /**
     * @OA\Post(
     *      path="/references/definitions",
     *      operationId="createReferenceDefinition",
     *      tags={"ReferenceDefinition"},
     *      summary="Create a new Reference Definition",
     *      description="Create a new Reference Definition",
     *      security={{"passport":{}}},
     *
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
     * @param ReferenceDefinitionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(ReferenceDefinitionRequest $request)
    {
        $request->user()->authorizeRoles(['admin']);

        $referenceDefinition = ReferenceDefinition::create([
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'user_email' => $request->user()->email,
            'title' => $request->get('title'),
            'type' => $request->get('type'),
            'schema' => $request->get('schema'),
            'settings' => $request->get('settings'),

        ]);

        return $this->response->item($referenceDefinition, new ReferenceDefinitionTransformer);
    }
}
