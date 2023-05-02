<?php

namespace App\Transformers;

use App\Models\Layout;
use App\Traits\VersionsTrait;
use League\Fractal\TransformerAbstract;

class SimpleLayoutTransformer extends TransformerAbstract
{
    use VersionsTrait;


    public function transform(Layout $layout): array
    {
        $version = 0;
        $versionDate = null;

        $version_id = $this->versionId ?? $layout->latestVersion->id ?? null;

        if($version_id ?? 0) {
            $currentVersion = $this->getVersionById($layout, $version_id);
            if($currentVersion){
                $version = $currentVersion['key'];
                $versionDate = $currentVersion['model']->created_at;
            }
        }

        $templateVersion = null;
        if($layout->article_template_version_id) {
            $checkTemplateVersion = $this->getVersionById($layout->template, $layout->article_template_version_id);
            if($checkTemplateVersion) {
                $templateVersion = $checkTemplateVersion['key'];
            }
        }

        return [
            'id' => (int) $layout->id,
            'name' => (string) $layout->name,
            'template_id' => $layout->article_template_id,
            'template_version' => $templateVersion,
            'version_id' => $version_id ?? 0,
            'version' => $version,
            'version_date' => $versionDate,
        ];
    }
}
