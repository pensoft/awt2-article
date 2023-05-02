<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\Layouts\CreateLayoutRequest;
use App\Models\ArticleTemplates;
use App\Models\Layout;
use App\Traits\VersionsTrait;
use App\Transformers\LayoutTransformer;

class CreateLayoutController extends BaseController
{
    use VersionsTrait;
    /**
     * @OA\Post(
     *      path="/layouts",
     *      operationId="createLayout",
     *      tags={"Layout"},
     *      summary="Create a new layout",
     *      description="Create a new layout",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateLayoutRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Layout",
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
     * @param CreateLayoutRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(CreateLayoutRequest $request){
        $template = ArticleTemplates::findOrFail($request->get('article_template_id'));
        $templateVersionId = $this->setTemplateVersionId($template->id, $request->get('article_template_version', $template->lastVersion->id));
        $layout = Layout::create([
            'name' =>$request->get('name'),
            'article_template_id' =>$template->id,
            'article_template_version_id' => $templateVersionId,
            'citation_style_id' => $request->get('citation_style_id'),
            'rules' => $request->get('rules'),
            'schema_settings' => $request->get('schema_settings'),
            'settings' => $request->get('settings'),
        ]);

        $layout->fresh('template');

        return $this->response->item($layout, new LayoutTransformer);
    }
}
