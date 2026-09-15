<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Exception;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
        'is_protected', // Ditambahkan agar data bisa disimpan via Seeder
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

    /**
     * Mencegah akun terproteksi dihapus dari database
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->is_protected || $user->role === 'owner' || $user->role === 'admin') {
                throw new Exception("Akun utama/admin tidak dapat dihapus dari sistem.");
            }
        });
    }
}