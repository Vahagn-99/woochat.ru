<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $amo_chat_id
 * @property string $whatsapp_chat_id
 * @property string $instance_id
 * @property string $connection_id
 *
 * @property Instance $instance
 * @property AmoConnection $amoConnection
 */
final class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = [
        'amo_chat_id',
        'whatsapp_chat_id',
        'instance_id',
        'connection_id',
    ];

    public $timestamps = false;

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class, 'instance_id', 'id');
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo(AmoConnection::class, 'connection_id', 'account_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }
}
