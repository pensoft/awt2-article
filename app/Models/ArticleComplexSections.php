<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleComplexSections extends Model
{
    use SoftDeletes;

    protected $table = 'article_complex_sections';

    protected $fillable = [
        'article_section_id',
        'article_simple_section_id',
        'order',
        'version_id',
        'complex_section_version_id'
    ];

}
