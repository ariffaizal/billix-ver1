<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $title = 'Order History List';

        return view('orders.history', compact(['title']));
    }

    public function data(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $data = (new Orders)->getOrderByDateRange($start_date, $end_date);
        $ajax = [];
        foreach ($data as $t) {
            $id = $t->id_order;
            $sts = $t->order_status;
            $openbillSts = [2, 3, 4];
            $action = '';
            $linkDetail = '#';
            if ($sts == 0) {
                $linkDetail = route('orders.new', ['id_order' => $id]);
            } elseif ($sts == 1 || $sts == 5) {
                $linkDetail = route('orders.pay', ['id_order' => $id]);
            } elseif (in_array($sts, $openbillSts) && $t->order_type == 'open_bill') {
                $linkDetail = route('orders.openbill', ['id_order' => $id]);
            } else {
                $linkDetail = route('orders.view', ['id_order' => $id]);
            }
            $btnDetail = '<a href="'.$linkDetail.'" class="btn btn-sm btn-info" id="btnDetail">Detail</a>';
            $action = $btnDetail;

            $row = [
                'id' => "$id#[$t->order_type]",
                'time' => $t->order_time,
                'bill_name' => $t->bill_name,
                'subtotal' => $t->price_subtotal,
                'discount' => $t->price_discount,
                'total' => $t->price_total,
                'status' => $this->badgeStatus($this->orderStatus()[$sts])[$sts],
                'by' => $t->name,
                'action' => $action,
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }
}
