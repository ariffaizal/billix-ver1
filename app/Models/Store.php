<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';

    protected $primaryKey = 'id_store';

    protected $fillable = [
        'store_name',
        'store_desc',
        'store_address_1',
        'store_address_2',
        'store_phone',
        'store_email',
        'store_logo',
    ];
}
