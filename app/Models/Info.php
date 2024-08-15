<?php

namespace App\Models;

use App\Enums\InfoType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $infoable_id
 * @property string $infoable_type
 * @property array $payload
 * @property string $type
 *
 * @method static Builder whereType(InfoType $type)
 */
class Info extends Model
{
    use HasFactory;

    protected $table = 'info';

    protected $fillable = [
        'infoable_id',
        'infoable_type',
        'payload',
        'type',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public $timestamps = false;

    public function infoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeWhereType(Builder $query, InfoType $type): Builder
    {
        return $query->where('type', $type);
    }
}
