<?php

namespace App\Models;

use App\Enums\ArticleSectionTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVersionable\Versionable;
use Overtrue\LaravelVersionable\VersionStrategy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArticleSections extends Model
{
    use SoftDeletes,
        Versionable;

    protected $table = 'article_sections';

    protected array $versionable = [
        'schema',
        'template',
        'complex_section_settings',
        'label_read_only'
    ];

    protected string $versionStrategy = VersionStrategy::SNAPSHOT;

    protected $fillable = [
        'name',
        'label',
        'label_read_only',
        'schema',
        'template',
        'compatibility',
        'allow_compatibility',
        'complex_section_settings',
        'type',
        'order'
    ];

    protected $casts = [
        'schema' => 'json',
        'compatibility' => 'json',
        'type' => ArticleSectionTypes::class,
        'complex_section_settings' => 'json',
        'label_read_only' => 'boolean',
        'allow_compatibility' => 'boolean',
    ];

    /**
     * @param $version_id
     * @return BelongsToMany
     */
    public function sections($version_id = null): BelongsToMany
    {
        $relation =  $this->belongsToMany(
            ArticleSections::class,
            ArticleComplexSections::class,
            'article_section_id',
            'article_simple_section_id',
            'id',
            'id')->withPivot('version_id', 'complex_section_version_id','id')->orderBy('order');

        if ($version_id){
            $relation->wherePivot('complex_section_version_id', $version_id);
        }

        $relation->orderBy('order');

        return $relation;
    }

    /**
     * @param $version_id
     * @return BelongsToMany
     */
    public function sectionsRecursive($version_id = null): BelongsToMany
    {
        return $this->sections($version_id)->with('sectionsRecursive');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyComplex(Builder $query): Builder
    {
        return $query->where('type', ArticleSectionTypes::COMPLEX);
    }

    public function scopeExist(Builder $query, $data = []): Builder
    {
        return $this->where('label', $data['label'])
            ->where('label_read_only', $data['label_read_only'])
            ->where('schema', $data['schema'])
            ->where('template', $data['template'])
            ->where('complex_section_settings', $data['complex_section_settings'])
            ->where('type', $data['type']);
    }
}
