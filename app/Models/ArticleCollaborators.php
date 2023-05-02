<?php
namespace App\Models;

use App\Enums\ArticleCollaboratorTypes;
use Illuminate\Database\Eloquent\Model;

class ArticleCollaborators extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'user_name',
        'user_email',
        'added_by',
        'type'
    ];

    protected $casts = [
        'type' => ArticleCollaboratorTypes::class
    ];
}
