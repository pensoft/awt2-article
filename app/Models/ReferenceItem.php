<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelVersionable\Versionable;

/**
 * @method static whereId($id)
 * @method static where(string $string, $id)
 */
class ReferenceItem extends Model
{
    use SoftDeletes;
    use Versionable;
    use Uuids;

    protected $table = 'reference_items';

    protected $fillable = [
        'uuid',
        'title',
        'data',
        'reference_definition_id',
        'user_id',
        'user_name',
        'user_email',
    ];

    protected $casts = [
        'data' => 'json',
    ];


    /**
     * @return BelongsTo
     */
    public function reference_definition(): BelongsTo
    {
        return $this->belongsTo(ReferenceDefinition::class, 'reference_definition_id', 'id');
    }
}
