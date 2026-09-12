<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\PricesDiscounts;
use App\Models\Store;
use App\Models\TableActive;
use App\Models\Tax;
use App\Models\User;
use App\Models\UserShift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        $title = 'Orders List';

        return view('orders.list', compact(['title']));
    }

    public function pending()
    {
        $title = 'Pending List';
        $data = Orders::whereIn('order_status', [1, 5])->get();

        return view('mainapp.pending', compact(['title', 'data']));
    }

    public function success()
    {
        $title = 'Success List';
        $data = Orders::where('order_status', 2)->get();

        return view('mainapp.success', compact(['title', 'data']));
    }

    private function shiftActive()
    {
        return UserShift::where('shift_active', 1)->latest('id_user_shift')->first();
    }

    public function data()
    {
        $shift = $this->shiftActive();
        if (empty($shift)) {
            return response()->json(['data' => []]);
        }
        $data = (new Orders)->getOrderByShift($shift->id_user_shift);
        $ajax = [];
        foreach ($data as $t) {
            $id = $t->id_order;
            $sts = $t->order_status;
            $openbillSts = [2, 3, 4];
            $csTable = '';
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

            $btnDetail = '<a href="' . $linkDetail . '" class="btn btn-sm btn-info" id="btnDetail">Detail</a>';
            $btnDelete = ' <button class="btn btn-sm btn-outline-danger" id="btnDelete" data-id="' . $id . '"><i class="bi bi-trash"></i></button>';

            if ($sts == 0) {
                $action = $btnDetail . $btnDelete;
            } else {
                $action = $btnDetail;
            }

            if ($t->order_type != 'fnb_only') {
                $items = OrderItems::where('id_order', $id)->where('is_table', 1)->get();
                foreach ($items as $i) {
                    $csTable .= $i->table_name . ', ';
                }
            }

            $row = [
                'id' => "#$id [$t->order_type]",
                'time' => $t->order_time,
                'bill_name' => $t->bill_name,
                'cstable' => $csTable,
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

    public function create(Request $request)
    {
        $type = $request->post('type');
        $shift = $this->shiftActive();
        $insert = Orders::create([
            'order_type' => $type,
            'order_time' => now(),
            'created_by' => Auth::id(),
            'order_status' => 0,
            'id_user_shift' => $shift->id_user_shift,
        ]);

        return redirect()->route('orders.new', ['id_order' => $insert->id_order]);
    }

    public function new($id_order)
    {
        $orders = Orders::findOrFail($id_order);
        if ($orders->order_status != 0) {
            return redirect()->route('orders');
        }
        $title = 'New Orders';
        $data['orders'] = $orders;
        $data['member'] = Member::all();

        return view('orders.new', compact(['title', 'data']));
    }

    public function process(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
            'total' => 'required',
        ]);

        $iSubTotal = (int) $request->post('total');

        $order = Orders::findOrFail($request->post('id_order'));

        if ($order->order_type == 'open_bill') {

            $itemsTable = OrderItems::where('id_order', $order->id_order)
                ->where('is_table', 1)
                ->get();

            if (count($itemsTable) == 0) {
                return abort(500, 'No Table has been added');
            }

            $countItemNoPrice = OrderItems::where('id_order', $order->id_order)
                ->where('is_table', 1)
                ->whereNull('id_pr_openbill')
                ->count();

            if ($countItemNoPrice != 0) {
                return abort(500, 'Price not selected');
            }

            $orderStatus = 2;
            $bill_name = $request->post('bill_name');
            $member = $request->post('member');
            if (! empty($member)) {
                $dataMember = Member::find($member);
                $update = $order->update([
                    'id_member' => $dataMember->id_member,
                    'member_no' => $dataMember->member_no,
                    'member_name' => $dataMember->member_name,
                    'bill_name' => $dataMember->member_name,
                ]);
            } else {
                $order->update([
                    'bill_name' => $bill_name,
                ]);
            }
        } else if ($order->order_type == 'packages') {
            $itemsTable = OrderItems::where('id_order', $order->id_order)
                ->where('is_table', 1)
                ->get();

            if (count($itemsTable) == 0) {
                return abort(500, 'No Table has been added');
            }

            $countItemNoPrice = OrderItems::where('id_order', $order->id_order)
                ->where('is_table', 1)
                ->whereNull('id_pr_package')
                ->count();

            if ($countItemNoPrice != 0) {
                return abort(500, 'Price not selected');
            }

            $orderStatus = 1;
        } else {
            $orderStatus = 1;
        }

        $update = $order->update([
            'price_subtotal' => $iSubTotal,
            'price_total' => $iSubTotal,
            'order_status' => $orderStatus,
        ]);

        if ($update) {
            if ($order->order_type == 'open_bill') {
                $url = '/orders/openbill/' . $order->id_order;
            } else {

                $url = '/orders/pay/' . $order->id_order;
            }

            return response()->json(['url' => $url]);
        } else {
            return abort(500, $update);
        }
    }

    public function openbill($id_order)
    {
        $order = Orders::findOrFail($id_order);
        $title = 'Open Billing';
        $data['order'] = $order;
        $data['status'] = $this->badgeStatus($this->orderStatus()[$order->order_status])[$order->order_status];

        return view('orders.open', compact(['title', 'data']));
    }

    public function estimateOpenBillPrice($id_order)
    {
        $order = Orders::findOrFail($id_order);
        $items = OrderItems::where('id_order', $order->id_order)->get();
        $time_stop = Carbon::now();
        $total = 0;
        foreach ($items as $i) {
            if ($i->is_table == 1) {
                $start_time = $i->openbill_time_start;
                $time_start = Carbon::parse($start_time);
                $time_total = (int) $time_start->diffInMinutes($time_stop);
                $total += (int) $i->openbill_price * $time_total;
            } else {
                $total += $i->fnb_amount;
            }
        }

        return response()->json(['estimate' => $total]);
    }

    public function processOpenBill(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
            'bill_name' => 'required',
        ]);

        $order = Orders::findOrFail($request->post('id_order'));
        $item_amount = OrderItems::where('id_order', $order->id_order)->sum('items_amount');

        $update = $order->update([
            'bill_name' => $request->post('bill_name'),
            'price_subtotal' => $item_amount,
            'price_total' => $item_amount,
            'order_status' => 5,
        ]);
        if ($update) {
            return redirect()->route('orders.pay', ['id_order' => $order->id_order]);
        } else {
            return abort(500, $update);
        }
    }

    public function payment($id_order)
    {
        $title = 'Payment';
        $order = Orders::findOrFail($id_order);
        $data['order'] = $order;
        $data['status'] = $this->badgeStatus($this->orderStatus()[$order->order_status])[$order->order_status];
        $data['items'] = (new OrderItemsController)->getViewItems($order->id_order);

        $data['discount'] = PricesDiscounts::all();
        $data['tax'] = Tax::latest()->first();
        // Data Member
        $data['member'] = Member::all();

        return view('orders.payment', compact(['title', 'data']));
    }

    public function statusToDraft(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
        ]);

        $order = Orders::findOrFail($request->post('id_order'));
        $order->update([
            'order_status' => 0,
        ]);

        return redirect()->route('orders.new', ['id_order' => $order->id_order]);
    }

    public function payment_confirmation(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
            'payment_method' => 'required',
            'discount' => 'required',
            'vat' => 'required',
            'total' => 'required',
            'cash' => 'required',
            'change' => 'required',
        ]);

        $order = Orders::findOrFail($request->post('id_order'));

        $bill_name = $request->post('bill_name');
        $pay_method = $request->post('payment_method');
        $diskon = $request->post('discount');
        $vat = $request->post('vat');
        $total = $request->post('total');
        $cash = $request->post('cash');
        $change = $request->post('change');
        $member = $request->post('member');

        if ($order->order_type == 'packages') {
            $orderStatus = 2;
            $itemsTable = OrderItems::where('id_order', $order->id_order)
                ->where('is_table', 1)
                ->get();

            foreach ($itemsTable as $i) {
                TableActive::updateOrCreate(
                    [
                        'id_table' => $i->id_table,
                    ],
                    [
                        'id_order' => $i->id_order,
                        'is_openbill' => 0,
                        'time_limit' => $i->package_time_limit,
                        'is_active' => 0,
                        'is_started' => 0,
                    ]
                );
            }
        } else {
            $orderStatus = 9;
        }

        $update = $order->update([
            'order_status' => $orderStatus,
            'bill_name' => $bill_name,
            'pay_method' => $pay_method,
            'price_discount' => $diskon,
            'price_vat' => $vat,
            'price_total' => $total,
            'cash_tendered' => $cash,
            'cash_change' => $change,
        ]);

        if (! empty($member)) {
            $dataMember = Member::find($member);
            $update = $order->update([
                'id_member' => $dataMember->id_member,
                'member_no' => $dataMember->member_no,
                'member_name' => $dataMember->member_name,
                'bill_name' => $dataMember->member_name,
            ]);
        }

        return response()->json($update);
    }

    public function view($id_order)
    {
        $order = Orders::findOrFail($id_order);
        $stsOrder = [2, 3, 7, 8, 9];
        if (! in_array($order->order_status, $stsOrder)) {
            return abort(404);
        }
        $title = 'View Order #' . $order->id_order;
        $data['order'] = $order;
        $data['status'] = $this->badgeStatus($this->orderStatus()[$order->order_status])[$order->order_status];
        $data['items'] = (new OrderItemsController)->getViewItems($order->id_order);

        if ($order->order_status == 7) {
            return view('orders.view_refund', compact(['title', 'data']));
        }

        return view('orders.view', compact(['title', 'data']));
    }

    public function print($id_order)
    {
        $order = Orders::findOrFail($id_order);
        $stsOrder = [2, 3, 7, 8, 9];
        if (! in_array($order->order_status, $stsOrder)) {
            return abort(404, 'Tidak ditemukan!');
        }
        $title = 'Print Order #' . $order->id_order;
        $data['order'] = $order;
        $data['status'] = $this->badgeStatus($this->orderStatus()[$order->order_status])[$order->order_status];
        $data['items'] = (new OrderItemsController)->getViewItems($order->id_order);
        $data['user'] = User::find($order->created_by);
        $data['store'] = Store::find(1);

        return view('orders.print_bill', compact(['title', 'data']));
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $order = Orders::findOrFail($request->post('id'));
        $update = $order->update([
            'order_status' => 8,
            'cancel_time' => now(),
        ]);

        return response()->json($update);
    }

    public function processRefund(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $order = Orders::findOrFail($request->post('id'));
        if ($order->order_type == 'fnb_only' && $order->order_status == 9) {
            $insert = DB::transaction(function () use ($order) {

                $newOrder = $order->replicate();
                $newOrder->order_status = 7;
                $newOrder->order_time = now();
                $newOrder->refund_order_id = $order->id_order;
                $newOrder->created_at = now();
                $newOrder->updated_at = now();
                $newOrder->save();

                $orderItems = OrderItems::where('id_order', $order->id_order)->get();
                foreach ($orderItems as $item) {
                    $newItem = $item->replicate();
                    $newItem->id_order = $newOrder->id_order;
                    $newItem->created_at = now();
                    $newItem->updated_at = now();
                    $newItem->save();
                }

                $order->update([
                    'has_refunded' => 1,
                ]);

                return $newOrder->id_order;
            });

            return response()->json(['new_order_id' => $insert]);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $order = Orders::findOrFail($id);
        if ($order->order_status != 0) {
            return response()->json(['error' => 'Order cannot be deleted!']);
        }
        $delete = DB::transaction(function () use ($order) {
            OrderItems::where('id_order', $order->id_order)->delete();
            $order->delete();
        });

        return response()->json($delete);
    }
}
