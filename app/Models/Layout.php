<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVersionable\Versionable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layout extends Model
{
    use SoftDeletes,
        Versionable;

    protected array $dontVersionable = [
        'settings'
    ];

    protected array $versionable = [
        'name',
        'article_template_id',
        'article_template_version_id',
        'citation_style_id',
        'schema_settings',
        'rules',
    ];

    protected $fillable = [
        'name',
        'article_template_id',
        'article_template_version_id',
        'citation_style_id',
        'schema_settings',
        'rules',
        'settings'
    ];

    protected $casts = [
        'rules' => 'json',
        'settings' => 'json',
    ];
    /**
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ArticleTemplates::class, 'article_template_id', 'id');
    }

    public function citation_style(): BelongsTo
    {
        return $this->belongsTo(CitationStyle::class, 'citation_style_id', 'id');
    }
}
