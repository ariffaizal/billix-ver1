<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricesDiscounts extends Model
{
    protected $table = 'prices_discounts';

    protected $primaryKey = 'id_pr_discount';

    protected $fillable = [
        'name',
        'price',
        'member_only',
    ];
}
