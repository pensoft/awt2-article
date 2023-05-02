<?php

namespace App\Traits;

use App\Models\ArticleSections;
use App\Models\ArticleTemplates;
use Illuminate\Database\Eloquent\Model;

trait VersionsTrait
{
    protected function getVersionId(Model $model, $key = null){

        if(is_null($key)) {
            return $model->latestVersion->id;
        }

        $versions = $model->versions->reverse()->values();
        $version = $versions[$key - 1];

        return $version->id ?? $model->latestVersion->id;
    }

    protected function getTemplateVersionId($id, $key = null){
        $model = ArticleTemplates::find($id);

        return $this->getVersionId($model, $key);
    }

    protected function getSectionVersionId($id, $key = null){
        $model = ArticleSections::find($id);

        return $this->getVersionId($model, $key);
    }

    protected function setTemplateVersionId($id, $key = null){
        $model = ArticleTemplates::find($id);
        if($key == '') {
            return null;
        }

        return $this->getVersionId($model, $key);
    }

    protected function setSectionVersionId($id, $key = null){
        $model = ArticleSections::find($id);
        if($key == '') {
            return null;
        }
        return $this->getVersionId($model, $key);
    }

    protected function getVersionById($model, $version_id){
        $versions = $model->versions->reverse();
        $collection = collect([]);
        $versions->map(fn ($v) => $collection->push($v));

        $key = $collection->search(fn ($v) => $v->id === $version_id);
        if($key !== false) {
            return [
                'key' => $key + 1,
                'model' => $collection[$key]
            ];
        }

        return null;
    }
}
