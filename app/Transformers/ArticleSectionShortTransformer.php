<?php

namespace App\Transformers;

use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use League\Fractal\TransformerAbstract;

class ArticleSectionShortTransformer extends TransformerAbstract
{
    /**
     * @param ArticleSections $section
     * @return array
     */
    public function transform(ArticleSections $section): array
    {
        $version_id = $section->pivot?->version_id;

        if($version_id){
            $section = $section->getVersion($version_id)->revertWithoutSaving();
        }


        $version = 0;
        $versionDate = null;
        if($section->latestVersion->id ?? 0) {
            $versions = $section->versions->reverse();
            $collection = collect([]);
            $versions->map(fn($v) => $collection->push($v));

            $key = $collection->search(fn($v) => $v->id === ($version_id ?? $section->latestVersion->id));
            $version = $key + 1;
            $versionDate = $collection[$key]->created_at;
        }

        return [
            'id' => (int) $section->id,
            'name' => (string) $section->name,
            'label' => (string) $section->label,
            'type' =>  $section->type,
            'version' => $version,
            'version_date' => $versionDate,
            'created_at' => $section->created_at,
        ];
    }
}
