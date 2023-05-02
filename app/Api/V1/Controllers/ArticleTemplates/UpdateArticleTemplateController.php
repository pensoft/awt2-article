<?php

namespace App\Api\V1\Controllers\ArticleTemplates;

use App\Api\V1\Controllers\BaseController;
use App\Api\V1\Requests\ArticleTemplates\CreateArticleTemplateRequest;
use App\Models\ArticleTemplates;
use App\Traits\VersionsTrait;
use App\Transformers\ArticleTemplateTransformer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UpdateArticleTemplateController extends BaseController
{
    use VersionsTrait;
    /**
     * @OA\Put(
     *      path="/articles/templates/{id}",
     *      operationId="updateArticleTemplate",
     *      tags={"ArticleTemplate"},
     *      summary="Update the article template",
     *      description="Update the article template",
     *      security={{"passport":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Article template id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *
     * @param CreateArticleTemplateRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function __invoke(CreateArticleTemplateRequest $request, $id)
    {
        try {

            $articleTemplate = ArticleTemplates::findOrFail($id);

            $articleTemplateVersionId = $request->get('version_id');

            $oldSections = $articleTemplate->sections($articleTemplateVersionId)->get();

            $oldSectionsCollection = $this->convertToSimple($oldSections, ['rules' => serialize(json_decode($articleTemplate->rules ?? [], true))])->map(fn($item) => serialize($item));

            $schema = collect(['rules' => serialize($request->get('rules'))]);
            foreach($request->get('schema', []) as $key=>$value){
                $schema->push([
                    'article_section_id' => $value['id'],
                    'order' => $key,
                    'version_id' => $this->setSectionVersionId($value['id'], $value['version']),
                    'template_version_id' => $articleTemplateVersionId,
                    'settings' => json_encode($value['settings'], JSON_FORCE_OBJECT)
                ]);
            }

            $sectionsCollection = $schema->map(fn($item) => serialize($item));
            $isChanged = !$oldSectionsCollection->diff($sectionsCollection)->isEmpty();

            $update = [
                'name'=>$request->get('name'),
                'rules' => json_encode($request->get('rules'), JSON_FORCE_OBJECT)
            ];

            if($isChanged) {
               $update['changed_at'] = Carbon::now();
            }
            //\DB::enableQueryLog();
            $articleTemplate->fill($update)->save();

            unset($schema['rules']);
            if($isChanged){
                $articleTemplateVersionId = $articleTemplate->latestVersion->id;

                $schema = $schema->map(function($item) use ($articleTemplateVersionId) {
                    $item['template_version_id'] = $articleTemplateVersionId;
                    return $item;
                });

            }

            //\DB::enableQueryLog();
            $articleTemplate->sections($articleTemplateVersionId)->detach();
            $articleTemplate->sections($articleTemplateVersionId)->sync($schema);
            //ddh(\DB::getQueryLog());

            $articleTemplate->fresh('sectionsRecursive');

            return $this->response->item($articleTemplate, new ArticleTemplateTransformer);
        } catch (\Exception $exception) {
            return $this->response->errorNotFound();
        }
    }

    private function convertToSimple(Collection $object, $more = []): Collection
    {
        $result = collect($more);

        $object->each(function ($item) use (&$result) {
            $result->push([
            'article_section_id' => $item->pivot->article_section_id,
            'order' => $item->pivot->order,
            'version_id' => $item->pivot->version_id,
            'template_version_id' => $item->pivot->template_version_id,
            'settings' => $item->pivot->settings,
            ]);
        });

        return $result;

    }
}
