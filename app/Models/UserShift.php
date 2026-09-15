<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
