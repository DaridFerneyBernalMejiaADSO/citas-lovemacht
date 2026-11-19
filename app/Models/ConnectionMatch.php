<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConnectionMatch extends Model
{
    protected $table = 'matches';

    protected $primaryKey = 'id_match';

    public $timestamps = false;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
    ];

    protected function casts(): array
    {
        return [
            'id_match' => 'integer',
            'user_one_id' => 'integer',
            'user_two_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id', 'id_user');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id', 'id_user');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'match_id', 'id_match');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'match_id', 'id_match')->latestOfMany('created_at');
    }

    public function otherUser(int $userId): ?User
    {
        if ($this->user_one_id === $userId) {
            return $this->userTwo;
        }

        if ($this->user_two_id === $userId) {
            return $this->userOne;
        }

        return null;
    }
}
