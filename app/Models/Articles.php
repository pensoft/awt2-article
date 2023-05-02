<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static uuid($id)
 */
class Articles extends Model
{
    use SoftDeletes,
        Uuids;

    protected $table = 'articles';

    protected $fillable = [
        'uuid',
        'name',
        'user_id',
        'user_name',
        'user_email',
        'layout_version_id',
        'layout_id'
    ];

    /**
     * @return BelongsTo
     */
    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class, 'layout_id', 'id');
    }

    public function getTemplate(){

        if($this->layout){
            $layout = $this->layout->getVersion($this->layout_version_id)->revertWithoutSaving();
            if($layout->template) {
                return $layout->template->getVersion($layout->article_template_version_id)->revertWithoutSaving();
            }
        }

        return null;
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(ArticleCollaborators::class, 'article_id', 'uuid');
    }
}
