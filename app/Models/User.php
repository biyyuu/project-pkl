<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, \Spatie\Permission\Traits\HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',    
        'password',
        'recovery_email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if (blank($user->username)) {
                $base = $user->email ? Str::before($user->email, '@') : Str::slug($user->name ?? 'user');
                $username = $base ?: 'user';

                $suffix = 1;
                while (self::where('username', $username)->exists()) {
                    $username = $base . '-' . $suffix;
                    $suffix++;
                }

                $user->username = $username;
            }
        });
    }

    public function items()
    {   
        return $this->hasMany(Item::class, 'user_id');
    }
}
