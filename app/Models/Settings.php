<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $instance_id
 * @property string $pipeline_id
 * @property string $status_id
 */
final class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'instance_id',
        'pipeline_id',
        'status_id'
    ];

    public $timestamps = false;

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'id');
    }
}
