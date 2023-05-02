<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelVersionable\Versionable;

class ArticleTemplates extends Model
{
    use SoftDeletes,
        Versionable;

    protected $fillable = [
        'changed_at',
        'name',
        'rules'
    ];


    protected $casts = [
        'changed_at' => 'datetime',
        'schema' => 'json',
    ];

    protected array $versionable = [
        'changed_at',
        'rules'
    ];

    //protected string $versionStrategy = VersionStrategy::SNAPSHOT;

    public function sections($version_id = null){
        $relation = $this->belongsToMany(
            ArticleSections::class,
            ArticleTemplateSections::class,
            'article_template_id',
            'article_section_id',
            'id',
            'id')
            ->withPivot('version_id', 'template_version_id','order','settings','id');

        if ($version_id){
            $relation->wherePivot('template_version_id', $version_id);
        }

        $relation->orderBy('order');

        return $relation;
    }

    public function sectionsRecursive($version_id = null)
    {
        return $this->sections($version_id)->with('sectionsRecursive');
    }
}
