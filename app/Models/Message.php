<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $user_id
 * @property int $chat_id
 * @property string $message
 * @property bool $read
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'chat_id', 'message', 'read'
    ];

    protected $with = [ 'files' ];

    protected $casts = [
        'read' => 'boolean'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

}
