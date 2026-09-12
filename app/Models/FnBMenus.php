<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FnBMenus extends Model
{
    protected $table = 'fnb_menus';

    protected $primaryKey = 'id_fnb';

    protected $fillable = [
        'id_fnbcategory',
        'fnb_name',
        'fnb_price',
        'fnb_description',
    ];

    public function getMenu($category)
    {
        $menu = DB::table('fnb_menus', 'm')
            ->select(
                'm.*',
                'c.category_name',
            )
            ->leftJoin('fnb_category as c', 'c.id_fnbcategory', '=', 'm.id_fnbcategory');
        if ($category != 'all') {
            $menu->where('m.id_fnbcategory', $category);
        }
        $menu->orderBy('m.id_fnbcategory');

        return $menu->get();
    }

    // public function getProductAvailable($id_order)
    // {
    //     return DB::table('products')
    //         ->whereNotIn('id_product', $this->getProductInOrder($id_order))
    //         ->get();
    // }

    // private function getProductInOrder($id_order)
    // {
    //     return DB::table('order_items')
    //         ->select('id_product')
    //         ->where('id_order', $id_order);
    // }
}
