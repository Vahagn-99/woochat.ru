<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $instance_id
 * @property int $pipeline_id
 * @property ?int $source_id
 * @property ?int $name
 */
final class Settings extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'settings';

    protected $fillable = [
        'instance_id',
        'pipeline_id',
        'source_id',
        'name',
    ];

    public $timestamps = false;

    public function instance(): BelongsTo
    {
        return $this->belongsTo(WhatsappInstance::class, 'instance_id', 'id');
    }
}
