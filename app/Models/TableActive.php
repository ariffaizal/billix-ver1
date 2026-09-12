<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableActive extends Model
{
    protected $table = 'table_active';

    protected $primaryKey = 'id_table_active';

    protected $fillable = [
        'id_order',
        'id_table',
        'is_openbill',
        'time_start',
        'time_limit',
        'time_end',
        'is_active',
        'is_started',
    ];
}
