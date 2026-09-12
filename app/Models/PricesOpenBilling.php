<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricesOpenBilling extends Model
{
    protected $table = 'prices_openbills';

    protected $primaryKey = 'id_pr_openbill';

    protected $fillable = [
        'name',
        'price',
    ];
}
