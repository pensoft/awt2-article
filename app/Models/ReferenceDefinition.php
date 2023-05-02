<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ReferenceDefinition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'schema',
        'settings',
        'user_id',
        'user_name',
        'user_email',
    ];

    protected $casts = [
        'schema' => 'json',
        'settings' => 'json',
    ];
}
