<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserReportController extends Controller
{
    public function index()
    {
        $title = 'User Report';

        return view('reports.by_user', compact(['title']));
    }

    public function data(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $data = User::all();
        $ajax = [];

        foreach ($data as $t) {
            $id = $t->id;
            $sum = $this->sumOrder($id, $start_date, $end_date);
            $count = $this->countOrder($id, $start_date, $end_date);
            $refund = $this->sumOrderRefund($id, $start_date, $end_date);
            if ($count == 0) {
                $divcount = 1;
                $totalOrder = 0;
            } else {
                $divcount = $count;
                $totalOrder = $count;
            }
            $gross = $sum->total_price + $sum->total_discount + $refund;
            $net = $gross - $sum->total_discount - $refund;
            $row = [
                'name' => $t->name,
                'gross_sales' => $gross,
                'total_refund' => $refund,
                'total_discount' => $sum->total_discount,
                'total_net' => $net,
                'total_vat' => $sum->total_vat,
                'total_order' => $totalOrder,
                'average' => $net / $divcount,
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    private function sumOrder($id_user, $start_date, $end_date)
    {
        $sumOrders = DB::table('orders')
            ->select([
                DB::raw('SUM(price_subtotal) as total_subtotal'),
                DB::raw('SUM(price_discount) as total_discount'),
                DB::raw('SUM(price_vat) as total_vat'),
                DB::raw('SUM(price_total) as total_price'),
            ])
            ->where('created_by', $id_user)
            ->where('order_status', 9);
        if ($start_date == $end_date) {
            $sumOrders->whereDate('created_at', '=', $start_date);
        } else {
            $sumOrders->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $sumOrders->first();
    }

    private function sumOrderRefund($id_user, $start_date, $end_date)
    {
        $sumOrders = DB::table('orders')
            ->where('created_by', $id_user)
            ->where('order_status', 7);
        if ($start_date == $end_date) {
            $sumOrders->whereDate('created_at', '=', $start_date);
        } else {
            $sumOrders->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $sumOrders->sum('price_total');
    }

    private function countOrder($id_user, $start_date, $end_date)
    {
        $count = DB::table('orders')
            ->where('created_by', $id_user)
            ->where('order_status', 9);
        if ($start_date == $end_date) {
            $count->whereDate('created_at', '=', $start_date);
        } else {
            $count->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $count->count('id_order');
    }
}
