<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleTemplateSections extends Model
{
    use SoftDeletes;

    protected $table = 'article_template_sections';

    protected $fillable = [
        'article_template_id',
        'article_section_id',
        'order',
        'version_id',
        'article_template_id',
        'settings'
    ];

}
