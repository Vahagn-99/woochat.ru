<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $amo_message_id
 * @property string $whatsapp_message_id
 * @property string $chat_id
 * @property string $from
 * @property string $to
 *
 * @property-read \App\Models\AmoInstance $amoInstance
 *
 */
final class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $fillable = [
        'amo_message_id',
        'whatsapp_message_id',
        'chat_id',
        'from',
        'to'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }
}
