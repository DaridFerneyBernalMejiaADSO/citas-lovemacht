<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Swipe extends Model
{
    protected $table = 'swipes';

    protected $primaryKey = 'id_swipe';

    public $timestamps = false;

    protected $fillable = [
        'swiper_id',
        'target_id',
        'action',
    ];

    protected function casts(): array
    {
        return [
            'id_swipe' => 'integer',
            'swiper_id' => 'integer',
            'target_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function swiper(): BelongsTo
    {
        return $this->belongsTo(User::class, 'swiper_id', 'id_user');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id', 'id_user');
    }
}
