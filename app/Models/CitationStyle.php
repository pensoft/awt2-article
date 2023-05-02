<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static updateOrCreate(array $array, $data)
 * @method static first()
 */
class CitationStyle extends Model
{

    protected $table = 'citation_styles';

    protected $fillable = [
        'name',
        'title',
        'title_short',
        'style_updated',
        'content'
    ];

    protected $casts = [
        'style_updated' => 'datetime'
    ];
}
