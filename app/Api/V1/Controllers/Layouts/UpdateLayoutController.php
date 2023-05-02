<?php

namespace App\Api\V1\Controllers\Layouts;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\UpdateArticleSectionRequest;
use App\Api\V1\Requests\Layouts\UpdateLayoutRequest;
use App\Models\ArticleTemplates;
use App\Models\Layout;
use App\Traits\VersionsTrait;
use App\Transformers\LayoutTransformer;

class UpdateLayoutController extends BaseController
{
    use VersionsTrait;

    /**
     * @OA\Put(
     *      path="/layouts/{id}",
     *      operationId="updateLayout",
     *      tags={"Layout"},
     *      summary="Update the layout",
     *      description="Update the layouts",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Layout id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateLayoutRequest")
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
     * @param UpdateArticleSectionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(UpdateLayoutRequest $request, $id)
    {
        try {
            $template = ArticleTemplates::findOrFail($request->get('article_template_id'));
            $layout = Layout::findOrFail($id);

            $templateVersionId = $this->setTemplateVersionId($template->id, $request->get('article_template_version', $template->lastVersion->id));

            $layout->fill([
                'name'=>$request->get('name'),
                'article_template_id'=>$template->id,
                'article_template_version_id' => $templateVersionId,
                'citation_style_id' => $request->get('citation_style_id'),
                'rules' => $request->get('rules'),
                'schema_settings' => $request->get('schema_settings'),
                'settings' => $request->get('settings'),
            ])->save();

            $layout->fresh();

            return $this->response->item($layout, new LayoutTransformer());
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }
}
