<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricesPackages extends Model
{
    protected $table = 'prices_packages';

    protected $primaryKey = 'id_pr_package';

    protected $fillable = [
        'name',
        'price',
        'time_limit',
    ];
}
