<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'user_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'id_user' => 'integer',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'id_user');
    }

    public function swipesSent(): HasMany
    {
        return $this->hasMany(Swipe::class, 'swiper_id', 'id_user');
    }

    public function swipesReceived(): HasMany
    {
        return $this->hasMany(Swipe::class, 'target_id', 'id_user');
    }

    public function supportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class, 'user_id', 'id_user');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id', 'id_user');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
