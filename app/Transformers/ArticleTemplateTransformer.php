<?php

namespace App\Transformers;

use App\Models\ArticleTemplates;
use League\Fractal\TransformerAbstract;

class ArticleTemplateTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'sections'
    ];

    protected $versionId;
    protected $currentVersion;

    public function __construct($versionId = null){
         $this->versionId =  $versionId;
    }
    /**
     * @param ArticleTemplates $template
     * @return array
     */
    public function transform(ArticleTemplates $template): array
    {
        $version = 0;
        $versionDate = null;

        $version_id = $this->versionId ?? $template->latestVersion->id ?? null;

        if($version_id){
            $section = $template->getVersion($version_id)->revertWithoutSaving();
        }

        if($version_id ?? 0) {
            $versions = $template->versions->reverse();
            $collection = collect([]);
            $versions->map(fn ($v) => $collection->push($v));

            $key = $collection->search(fn ($v) => $v->id === $version_id);

            if($key !== false) {
                $version = $key + 1;
                $versionDate = $collection[$key]->created_at;
            }
        }

       $rules = null;
        try { $rules = json_decode($template->rules, true); } catch (\Exception $exception){ /*ddh($exception->getMessage());*/}

        $this->currentVersion = $version_id;
        $this->versionId = null;

        return [
            'id' => (int) $template->id,
            'name' => (string) $template->name,
            'version_id' => $version_id ?? 0,
            'version' => $version,
            'version_date' => $versionDate,
            'rules' => $rules,
            'created_at' => $template->created_at
        ];
    }


    public function includeSections(ArticleTemplates $template)
    {
        $sectionsRecursive = $template->sectionsRecursive($this->currentVersion)->get();
        if(!$sectionsRecursive) return null;

        return $this->collection($sectionsRecursive, new ArticleSectionTransformer);
    }
}
