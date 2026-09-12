<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsReportController extends Controller
{
    public function index()
    {
        $title = 'Items Report';

        return view('reports.by_items', compact(['title']));
    }

    public function data(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $orderItems = DB::table('order_items', 'i')
            ->select([
                'i.id_fnb',
                DB::raw('SUM(i.fnb_qty) as items_sold'),
                DB::raw('SUM(i.fnb_amount) as items_amount'),
            ])
            ->groupBy('i.id_fnb');
        if ($start_date == $end_date) {
            $orderItems->whereDate('i.created_at', '=', $start_date);
        } else {
            $orderItems->whereBetween('i.created_at', [$start_date, $end_date]);
        }

        $menu = DB::table('fnb_menus', 'm')
            ->select(
                'm.*',
                'c.category_name',
                'i.items_sold',
                'i.items_amount',
            )
            ->leftJoin('fnb_category as c', 'c.id_fnbcategory', '=', 'm.id_fnbcategory')
            ->leftJoinSub($orderItems, 'i', 'i.id_fnb', '=', 'm.id_fnb')
            ->orderBy('m.id_fnbcategory');
        $data = $menu->get();

        return response()->json(['data' => $data]);
    }

    public function chart(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $orderItems = DB::table('order_items', 'i')
            ->select([
                'i.id_fnb',
                DB::raw('SUM(i.fnb_qty) as items_sold'),
                DB::raw('SUM(i.fnb_amount) as items_amount'),
            ])
            ->groupBy('i.id_fnb')
            ->leftJoin('orders as o', 'o.id_order', '=', 'i.id_order')
            ->where('o.order_status', 9);
        if ($start_date == $end_date) {
            $orderItems->whereDate('i.created_at', '=', $start_date);
        } else {
            $orderItems->whereBetween('i.created_at', [$start_date, $end_date]);
        }

        $menu = DB::table('fnb_menus', 'm')
            ->select(
                'm.*',
                'c.category_name',
                'i.items_sold',
                'i.items_amount',
            )
            ->leftJoin('fnb_category as c', 'c.id_fnbcategory', '=', 'm.id_fnbcategory')
            ->leftJoinSub($orderItems, 'i', 'i.id_fnb', '=', 'm.id_fnb')
            ->orderByDesc('i.items_amount')
            ->limit(5)
            ->get();

        return response()->json(['data' => $menu]);
    }
}
