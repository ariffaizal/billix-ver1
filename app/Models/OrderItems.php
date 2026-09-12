<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';

    protected $primaryKey = 'id_order_item';

    protected $fillable = [
        'id_order_item',
        'id_order',
        'is_fnb',
        'id_fnb',
        'fnb_name',
        'fnb_price',
        'fnb_qty',
        'fnb_amount',
        'is_table',
        'id_table',
        'table_name',
        'id_pr_package',
        'package_name',
        'package_price',
        'package_time_limit',
        'id_pr_openbill',
        'openbill_name',
        'openbill_price',
        'openbill_time_start',
        'openbill_totaltime',
        'openbill_totalprice',
        'items_amount',
        'created_by',
    ];
}
