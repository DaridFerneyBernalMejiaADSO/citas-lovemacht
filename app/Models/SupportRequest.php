<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportRequest extends Model
{
    protected $table = 'support_requests';

    protected $primaryKey = 'id_request';

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'admin_response',
        'status',
        'responded_by',
    ];

    protected function casts(): array
    {
        return [
            'id_request' => 'integer',
            'user_id' => 'integer',
            'responded_by' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by', 'id_user');
    }
}
