<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'messages';

    protected $primaryKey = 'id_message';

    public $timestamps = false;

    protected $fillable = [
        'match_id',
        'sender_id',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'id_message' => 'integer',
            'match_id' => 'integer',
            'sender_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function connectionMatch(): BelongsTo
    {
        return $this->belongsTo(ConnectionMatch::class, 'match_id', 'id_match');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id_user');
    }
}
