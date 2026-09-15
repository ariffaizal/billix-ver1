<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserShift extends Model
{
    protected $table = 'user_shift';

    protected $primaryKey = 'id_user_shift';

    protected $fillable = [
        'id_user',
        'shift_start',
        'shift_end',
        'initial_capital',
        'cash_actual',
        'cash_out',
        'cash_out_info',
        'shift_info',
        'shift_active',
    ];

    // Relasi ke Model User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi ke Model Orders
    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'id_user_shift', 'id_user_shift');
    }
}