<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\CreateArticleSectionRequest;
use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use App\Traits\ArticleSectionsTrait;
use App\Traits\VersionsTrait;
use App\Transformers\ArticleSectionTransformer;

class CreateArticleSectionController extends BaseController
{

    use ArticleSectionsTrait, VersionsTrait;
    /**
     * @OA\Post(
     *      path="/articles/sections",
     *      operationId="createArticleSection",
     *      tags={"ArticleSection"},
     *      summary="Create a new article section",
     *      description="Create a new article section",
     *      security={{"passport":{}}},
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/CreateArticleSectionRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *            mediaType="application/x.article-api.v1+json",
     *            @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ArticleSection",
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
     * @param CreateArticleSectionRequest $request
     * @param ArticleSections $section
     * @return \Dingo\Api\Http\Response
     */
    public function __invoke(CreateArticleSectionRequest $request, ArticleSections $section)
    {

        $data = $this->prepareDataFromRequest($request);
        $articleSection = ArticleSections::create($data['simple']);
        if($data['simple']['type']  === ArticleSectionTypes::COMPLEX ) {
            $schema = [];
            foreach($data['complex'] as $key=>$value){
                $schema[] = [
                    'article_simple_section_id' => $value['id'],
                    'version_id' => $this->setSectionVersionId($value['id'], $value['version']),
                    'complex_section_version_id' => $articleSection->latestVersion->id
                ];
            }
            $articleSection->sections()->detach();
            $articleSection->sections()->sync($schema);
            $articleSection->sections->each(function($section, $index) use (&$data){
                $key = array_search($index, array_column($data['simple']['complex_section_settings'], 'index'));
                if($key !== false) {
                    $data['simple']['complex_section_settings'][$key]['pivot_id'] = $section->pivot->id;
                }
            });
            $articleSection->complex_section_settings = $data['simple']['complex_section_settings'];

            ArticleSections::withoutVersion(function () use ($articleSection) {
                $articleSection->save();
            });
        }

        $articleSection->fresh('sectionsRecursive');

        return $this->response->item($articleSection, new ArticleSectionTransformer);
    }
}
