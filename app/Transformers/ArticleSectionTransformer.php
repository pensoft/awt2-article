<?php

namespace App\Transformers;

use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use League\Fractal\TransformerAbstract;

class ArticleSectionTransformer extends TransformerAbstract
{
    /**
     * @param ArticleSections $section
     * @return array
     */
    public function transform(ArticleSections $section): array
    {
        $version_id = $section->pivot?->version_id;
        $pivotId = $section->pivot?->id;

        $settings = null;
        try {
            $settings = json_decode($section->pivot->settings);
        } catch (\Exception $exception){}

        $versionPreDefined = false;
        if($version_id){
            $section = $section->getVersion($version_id)->revertWithoutSaving();
            $versionPreDefined = true;
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

        $versionId = $version_id ?? $section->latestVersion->id ?? 0;

        return [
            'id' => (int) $section->id,
            'pivot_id'=> $pivotId,
            'name' => (string) $section->name,
            'label' => (string) $section->label,
            'label_read_only' => $section->label_read_only,
            'schema' =>  $section->schema,
            'sections' => $section->type->in([ArticleSectionTypes::COMPLEX])? $this->recursive($section->sectionsRecursive($versionId)->get()) : null,
            'template' =>  $section->template,
            'type' =>  $section->type,
            'version_id' => $versionId,
            'version' => $version,
            'version_pre_defined' => $versionPreDefined,
            'version_date' => $versionDate,
            'complex_section_settings' => $section->complex_section_settings ?? null,
            'settings' => $settings,
            'compatibility' => $section->compatibility,
            'compatibility_extended' => $this->getCompatibility($section),
            'allow_compatibility' => $section->allow_compatibility,
            'created_at' => $section->created_at,
        ];
    }

    private function recursive($collection){
        $result = [];

        $collection->each(function($item) use (&$result){
            $result[] = $this->transform($item);
        });
        return $result;
    }

    /**
     * @param $section
     * @return array
     */
    private function getCompatibility($section)
    {
        if($section->type->in([ArticleSectionTypes::COMPLEX])) {
            if(!$section->allow_compatibility) {
                return [];
            }

            if($section->compatibility['deny']['all'] ?? false) {
                return ArticleSections::select('id', 'name', 'label')
                    ->whereIn('id', $section->compatibility['allow']['values'] ?? [])->get()->map(fn ($item) => $this->extractCompatibilityData($item));
            } else {
                $notIn = [...$section->compatibility['deny']['values'] ?? [], $section->id];
                return ArticleSections::select('id', 'name', 'label')
                    ->whereNotIn('id', $notIn)->get()->map(fn ($item) => $this->extractCompatibilityData($item));
            }
        }

        return $section->compatibility;
    }

    /**
     * @param $item
     * @return mixed
     */
    private function extractCompatibilityData($item){
        $result = $item->toArray();

        if($item->latestVersion->id ?? 0) {
            $versions = $item->versions->reverse();
            $collection = collect([]);
            $versions->map(fn($v) => $collection->push($v));

            $key = $collection->search(fn($v) => $v->id === $item->latestVersion->id);
            $result['version'] = $key + 1;
            $result['version_date'] = $collection[$key]->created_at;
        }
        return $result;
    }
}
