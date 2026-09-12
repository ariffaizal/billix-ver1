<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnBCategory extends Model
{
    protected $table = 'fnb_category';

    protected $primaryKey = 'id_fnbcategory';

    protected $fillable = [
        'category_name',
    ];
}
