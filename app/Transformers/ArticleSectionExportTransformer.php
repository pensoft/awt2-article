<?php

namespace App\Transformers;

use App\Enums\ArticleSectionTypes;
use App\Models\ArticleSections;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class ArticleSectionExportTransformer extends TransformerAbstract
{
    /**
     * @param ArticleSections $section
     * @return array
     */
    public function transform(ArticleSections $section, $skipCompatibilityTransform = false): array
    {
        $version_id = $section->pivot ? $section->pivot->version_id ?? null : null;

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
            'compatibility' => $this->fullCompatibility($section->compatibility, $skipCompatibilityTransform),
            'allow_compatibility' => $section->allow_compatibility,
            'created_at' => $section->created_at
        ];
    }

    private function recursive($collection, $skipCompatibilityTransform = false)
    {
        $result = [];

        $collection->each(function ($item, $i) use (&$result, $skipCompatibilityTransform) {
            $result[] = $this->transform($item, $skipCompatibilityTransform);
        });


        return $result;
    }

    private function fullCompatibility($data, $skipCompatibilityTransform = false)
    {
        if($skipCompatibilityTransform) return $data;
        $data = json_decode(json_encode($data));
        if(
            $data
            && $data->allow
            && $data->allow->values
            && is_array($data->allow->values)
            && sizeof($data->allow->values
            ) > 0){
            $values = $data->allow->values;
            $sections = ArticleSections::whereIn('id', $values)->get();
            $data->allow->values = $this->recursive($sections, true);
        }

        if(
            $data
            && $data->deny
            && $data->deny->values
            && is_array($data->deny->values)
            && sizeof($data->deny->values
            ) > 0){
            $values = $data->deny->values;
            $sections = ArticleSections::whereIn('id', $values)->get();
            $data->deny->values = $this->recursive($sections, true);
        }


        return $data;
    }
}
