<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\FnBMenus;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Tables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsReportV2Controller extends Controller
{
    public function index()
    {
        $title = 'Items Report';

        return view('reports.by_items_v2', compact(['title']));
    }

    public function dataSummary(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if ($start_date == $end_date) {
            $query = Orders::whereDate('order_time', '=', $start_date);
        } else {
            $query = Orders::whereDate('order_time', '>=', $start_date)
                ->whereDate('order_time', '<=', $end_date);
        }

        $refundQuery = DB::query()->fromSub($query, 'q')->where('order_status', 7);
        $refund = $refundQuery->sum('q.price_total');

        $pluck = $query->where('order_status', 9)->pluck('id_order');

        $tables = OrderItems::whereIn('id_order', $pluck)->where('is_table', 1)->sum('items_amount');
        $fnb = OrderItems::whereIn('id_order', $pluck)->where('is_fnb', 1)->sum('items_amount');

        $discountQuery = DB::query()->fromSub($query, 'q')->where('order_status', 9);
        $discount = $discountQuery->sum('q.price_discount');

        $taxQuery = DB::query()->fromSub($query, 'q')->where('order_status', 9);
        $tax = $taxQuery->sum('q.price_vat');

        $totalIncome = $tables + $fnb;
        $netIncome = $totalIncome - $discount;
        $subtotal = $netIncome + $tax;
        $revenue = $subtotal - $refund;

        $data = [
            [
                'items' => 'Tables Income',
                'amount' => $tables
            ],
            [
                'items' => 'FnB Income',
                'amount' => $fnb
            ],
            [
                'items' => '<strong>Total Income</strong>',
                'amount' => $totalIncome
            ],
            [
                'items' => 'Discount',
                'amount' => -$discount
            ],
            [
                'items' => '<strong>Net Income</strong>',
                'amount' => $netIncome
            ],
            [
                'items' => 'VAT',
                'amount' => $tax
            ],
            [
                'items' => 'Sub Total',
                'amount' => $subtotal
            ],
            [
                'items' => 'Refund',
                'amount' => -$refund
            ],
            [
                'items' => '<strong>Revenue</strong>',
                'amount' => $revenue
            ],
        ];

        return response()->json(['data' => $data]);
    }

    public function dataByTables(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if ($start_date == $end_date) {
            $query = Orders::whereDate('order_time', '=', $start_date);
        } else {
            $query = Orders::whereDate('order_time', '>=', $start_date)
                ->whereDate('order_time', '<=', $end_date);
        }

        $pluck = $query->where('order_status', 9)->pluck('id_order');
        $getTables = Tables::all();
        $data = [];
        foreach ($getTables as $t) {
            $amount = OrderItems::whereIn('id_order', $pluck)->where('is_table', 1)->where('id_table', $t->id_table)->sum('items_amount');
            $data[] = [
                'items' => $t->table_name,
                'amount' => $amount
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function dataByFnb(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if ($start_date == $end_date) {
            $query = Orders::whereDate('order_time', '=', $start_date);
        } else {
            $query = Orders::whereDate('order_time', '>=', $start_date)
                ->whereDate('order_time', '<=', $end_date);
        }

        $pluck = $query->where('order_status', 9)->pluck('id_order');
        $getFnb = (new FnBMenus())->getMenu('all');
        $data = [];
        foreach ($getFnb as $t) {
            $amount = OrderItems::whereIn('id_order', $pluck)->where('is_fnb', 1)->where('id_fnb', $t->id_fnb)->sum('items_amount');
            $count = OrderItems::whereIn('id_order', $pluck)->where('is_fnb', 1)->where('id_fnb', $t->id_fnb)->count();
            $data[] = [
                'items' => $t->fnb_name,
                'category' => $t->category_name,
                'sold' => $count,
                'amount' => $amount
            ];
        }

        return response()->json(['data' => $data]);
    }
}
