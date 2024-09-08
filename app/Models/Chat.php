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
 * @property string $whatsapp_instance_id
 * @property string $amo_chat_instance_id
 *
 * @property WhatsappInstance $whatsappInstance
 */
final class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'amo_chat_id',
        'whatsapp_chat_id',
        'whatsapp_instance_id',
        'amo_chat_instance_id',
    ];

    public $timestamps = false;

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    public function whatsappInstance(): BelongsTo
    {
        return $this->belongsTo(WhatsappInstance::class, 'whatsapp_instance_id', 'id');
    }

    public function amoInstance(): BelongsTo
    {
        return $this->belongsTo(AmoInstance::class, 'amo_chat_instance_id', 'id');
    }
}
