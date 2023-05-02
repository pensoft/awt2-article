<?php

namespace App\Transformers;

use App\Models\CitationStyle;
use App\Models\Layout;
use App\Traits\VersionsTrait;
use League\Fractal\TransformerAbstract;

class LayoutTransformer extends TransformerAbstract
{
    use VersionsTrait;

    protected $defaultIncludes = [
        'template',
        'citation_style'
    ];

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
            'version_id' => $version_id ?? 0,
            'version' => $version,
            'version_date' => $versionDate,
            'rules' => $layout->rules,
            'schema_settings' => $layout->schema_settings,
            'settings' => $layout->settings,
            'template_version' => $templateVersion,
            'created_at' => $layout->created_at,
            'is_layout' => true
        ];
    }

    public function includeTemplate(Layout $layout): ?\League\Fractal\Resource\Item
    {
        $template = $layout->template;
        if( $layout->article_template_version_id ) {
            $template = $layout->template->getVersion($layout->article_template_version_id)->revertWithoutSaving();
        }

        if(!$template) return null;

        return $this->item($template, new ArticleTemplateTransformer($layout->article_template_version_id ?? null));
    }

    public function includeCitationStyle(Layout $layout): ?\League\Fractal\Resource\Item
    {
        $citationStyle = $layout->citation_style;
        if(!$citationStyle) return null;

        return $this->item($citationStyle, new CitationStyleTransformer);
    }
}
