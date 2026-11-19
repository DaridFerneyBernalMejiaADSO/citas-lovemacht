<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $primaryKey = 'id_profiles';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'birth_date',
        'gender',
        'city',
        'bio',
        'profile_photo',
        'is_demo',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_demo' => 'boolean',
            'user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

    public function getPhotoUrlAttribute(): string
    {
        $path = $this->profile_photo;

        if (! $path) {
            return asset('assets/images/perfiles/avatar.svg');
        }

        if (str_starts_with($path, 'profiles/')) {
            return Storage::disk('public')->url($path);
        }

        return asset(ltrim($path, '/'));
    }
}
