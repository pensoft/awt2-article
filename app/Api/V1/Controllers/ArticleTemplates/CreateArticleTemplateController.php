<?php

namespace App\Api\V1\Controllers\ArticleTemplates;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleTemplates\CreateArticleTemplateRequest;
use App\Models\ArticleSections;
use App\Models\ArticleTemplates;
use App\Traits\VersionsTrait;
use App\Transformers\ArticleTemplateTransformer;
use Carbon\Carbon;

class CreateArticleTemplateController extends BaseController
{
    use VersionsTrait;

    /**
     * @OA\Post(
     *      path="/articles/templates",
     *      operationId="createArticleTemplate",
     *      tags={"ArticleTemplate"},
     *      summary="Create a new article template",
     *      description="Create a new article template",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateArticleTemplateRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ArticleTemplate",
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
     * @param CreateArticleTemplateRequest $request
     * @param ArticleSections $section
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(CreateArticleTemplateRequest $request, ArticleTemplates $template)
    {
        $articleTemplate = ArticleTemplates::create([
            'name' => $request->get('name'),
            'changed_at' => Carbon::now(),
            'rules' => json_encode($request->get('rules'), JSON_FORCE_OBJECT)
        ]);
        $schema = [];
        foreach($request->get('schema', []) as $key=>$value){
            $schema[] = [
                'order' => $key,
                'article_section_id' => $value['id'],
                'version_id' => $this->setSectionVersionId($value['id'], $value['version']),
                'template_version_id' => $articleTemplate->latestVersion->id,
                'settings' => json_encode($value['settings'], JSON_FORCE_OBJECT)
            ];
        }
        $articleTemplate->sections($articleTemplate->latestVersion->id)->sync($schema);

        $articleTemplate->fresh('sectionsRecursive');

        return $this->response->item($articleTemplate, new ArticleTemplateTransformer);
    }
}
