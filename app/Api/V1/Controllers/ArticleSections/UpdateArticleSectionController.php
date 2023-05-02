<?php

namespace App\Api\V1\Controllers\ArticleSections;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleSections\CreateArticleSectionRequest;
use App\Api\V1\Requests\ArticleSections\UpdateArticleSectionRequest;
use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use App\Traits\ArticleSectionsTrait;
use App\Traits\VersionsTrait;
use App\Transformers\ArticleSectionTransformer;
use Dingo\Api\Exception\ValidationHttpException;
use OpenApi\Annotations as OA;

class UpdateArticleSectionController extends BaseController
{
    use ArticleSectionsTrait,
        VersionsTrait;
    /**
     * @OA\Put(
     *      path="/articles/sections/{id}",
     *      operationId="updateArticleSection",
     *      tags={"ArticleSection"},
     *      summary="Update the article section",
     *      description="Update the article section",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Article section id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateArticleSectionRequest")
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
     *
     * @param UpdateArticleSectionRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(UpdateArticleSectionRequest $request, $id)
    {
        try {
            $data = $this->prepareDataFromRequest($request);

            $articleSection = ArticleSections::findOrFail($id);
            $articleSection->fill($data['simple'])->save();

            if($data['simple']['type']  === ArticleSectionTypes::COMPLEX ) {

                //$complexSectionVersionId = $request->get('version_id');
                $complexSectionVersionId = $articleSection->latestVersion->id;

                $complex = [];
                $sections = [];
                foreach($data['complex'] as $key=>$value){
                    $complex[] = [
                        'article_simple_section_id' => $value['id'],
                        'version_id' => $this->setSectionVersionId($value['id'], $value['version']),
                        'complex_section_version_id' => $complexSectionVersionId
                    ];
                    $sections[] = $value['id'];
                }

                // Validation part
                $relatedSections = ArticleSections::with('sectionsRecursive')->whereIn('id', $sections)->get();
                if($flag = $this->checkForCircularDependencies($relatedSections, $articleSection->id)) {
                    $request->request->add(['circularDependencies' => $this->checkForCircularDependencies($relatedSections, $articleSection->id)]);
                    $validator = $this->getValidationFactory()
                        ->make(
                            $request->all(),
                            ['circularDependencies' => ['required','lt:1']],
                            ["Possible circular dependency with section Id #".$flag]
                        );
                    if ($validator->fails()) {
                        throw new ValidationHttpException(
                            $validator->errors()
                        );
                    }
                }

                $articleSection->sections($complexSectionVersionId)->detach();
                $articleSection->sections($complexSectionVersionId)->sync($complex);

                $articleSection->sections($complexSectionVersionId)->each(function($section, $index) use (&$data){

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
        catch(ValidationHttpException $exception) {
            throw new ValidationHttpException(
                $validator->errors()
            );
        }
        catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }

    function checkForCircularDependencies($sec, $searchForId, $parent = null){
        $flag = 0;
        $sec->each(function($section) use ($searchForId, $sec, $parent, &$flag) {
            if($section->id == $searchForId) {
                $flag = $parent? $parent->id : $searchForId;
            }
            if($section->type->in([ArticleSectionTypes::COMPLEX]) && !$flag){
                $flag = $this->checkForCircularDependencies($section->sectionsRecursive, $searchForId, $section);
            }
        });
        return $flag;
    }
}
