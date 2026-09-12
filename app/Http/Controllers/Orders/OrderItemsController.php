<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Models\FnBMenus;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\PricesOpenBilling;
use App\Models\PricesPackages;
use App\Models\Tables;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function getItemsList($id_order)
    {
        $order = Orders::find($id_order);

        $data = OrderItems::where('id_order', $id_order)
            ->orderBy('is_table', 'desc')
            ->get();

        $pricePackage = PricesPackages::all();
        $priceOpenbill = PricesOpenBilling::all();

        $ajax = [];
        foreach ($data as $t) {
            $id = $t->id_order_item;

            $items = '';
            $qty_package = '';
            $price = '';
            $priceSelect = '';
            $tablePrice = '';

            $inputQtyFnb = '<input type="number" min="1" value="'.$t->fnb_qty.'" class="fnbQty" data-id="'.$id.'" style="width: 50px;">';

            if ($order->order_type == 'packages') {
                $priceSelect = '<select class="form-select pilihpaket" id="pilihpaket'.$id.'">';
                $priceSelect .= '<option value="">-- pilih paket --</option>';
                foreach ($pricePackage as $p) {
                    $optVal = 'pkg|'.$id.'|'.$p->id_pr_package;
                    $priceSelect .= '<option value="'.$optVal.'"'.($t->id_pr_package == $p->id_pr_package ? 'selected="selected"' : '').'>'.$p->name.'</option>';
                }
                $priceSelect .= '</select>';

                $tablePrice = $t->package_price;
            }

            if ($order->order_type == 'open_bill') {
                $priceSelect = '<select class="form-select pilihpaket" id="pilihpaket'.$id.'">';
                $priceSelect .= '<option value="">-- pilih harga --</option>';
                foreach ($priceOpenbill as $p) {
                    $optVal = 'opb|'.$id.'|'.$p->id_pr_openbill;
                    $priceSelect .= '<option value="'.$optVal.'"'.($t->id_pr_openbill == $p->id_pr_openbill ? 'selected="selected"' : '').'>'.$p->name.'</option>';
                }
                $priceSelect .= '</select>';

                $tablePrice = $t->openbill_price;
            }

            if ($t->is_table == 1) {
                $items = $t->table_name;
                $qty_package = $priceSelect;
                $price = $tablePrice;
            }

            if ($t->is_fnb == 1) {
                $items = $t->fnb_name;
                $qty_package = $inputQtyFnb;
                $price = $t->fnb_price;
            }

            $amount = $t->items_amount;

            $row = [
                'items' => $items,
                'qty' => $qty_package,
                'price' => $price,
                'amount' => $amount,
                'action' => '<button class="btn btn-sm btn-outline-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i></button>',
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function getOpenBillItems($id_order)
    {
        $order = Orders::find($id_order);

        $data = OrderItems::where('id_order', $id_order)
            ->orderBy('is_table', 'desc')
            ->get();

        $ajax = [];
        foreach ($data as $t) {
            $id = $t->id_order_item;
            $items = '';
            $qty_package = '';
            $price = '';
            $priceSelect = '';
            $tablePrice = '';
            $action = '';
            $btnTransfer = '';

            $inputQtyFnb = '<input type="number" min="1" value="'.$t->fnb_qty.'" class="fnbQty" data-id="'.$id.'" style="width: 50px;">';

            // if ($order->order_type == 'packages') {
            //     $priceSelect = $t->package_name;
            //     $tablePrice = $t->package_price;
            // }

            if ($order->order_type == 'open_bill') {
                $priceSelect = $t->openbill_name;
                if ($order->order_status == 4) {
                    $priceSelect = $t->openbill_totaltime.' menit';
                }
                $tablePrice = $t->openbill_price;
            }

            if ($t->is_table == 1) {
                if ($order->order_status == 3) {
                    $btnTransfer = ' <button class="btn btn-sm btn-outline-info btnTransfer"
                    data-id="'.$id.'">
                    <i class="bi bi-arrow-left-right"></i> Transfer
                </button>';
                }
                $items = $t->table_name;
                $qty_package = $priceSelect;
                $price = $tablePrice;
            }

            if ($t->is_fnb == 1) {
                $items = $t->fnb_name;
                $qty_package = $inputQtyFnb;
                $price = $t->fnb_price;
                $action = '<button class="btn btn-sm btn-outline-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i></button>';
            }

            $amount = $t->items_amount;

            $row = [
                'items' => $items.$btnTransfer,
                'qty' => $qty_package,
                'price' => $price,
                'amount' => $amount,
                'action' => $action,
            ];
            $ajax[] = $row;
        }

        // return $ajax;
        return response()->json(['data' => $ajax]);
    }

    public function getViewItems($id_order)
    {
        $order = Orders::find($id_order);

        $data = OrderItems::where('id_order', $id_order)
            ->orderBy('is_table', 'desc')
            ->get();

        $ajax = [];
        foreach ($data as $t) {
            $items = '';
            $qty_package = '';
            $price = '';
            $priceSelect = '';
            $tablePrice = '';

            $qtyFnb = $t->fnb_qty;

            if ($order->order_type == 'packages') {
                $priceSelect = $t->package_name;
                $tablePrice = $t->package_price;
            }

            if ($order->order_type == 'open_bill') {
                $priceSelect = $t->openbill_name;
                if ($order->order_status == 5 || $order->order_status == 9) {
                    $priceSelect = $t->openbill_totaltime.' menit';
                }
                $tablePrice = $t->openbill_price;
            }

            if ($t->is_table == 1) {
                $items = $t->table_name;
                $qty_package = $priceSelect;
                $price = $tablePrice;
            }

            if ($t->is_fnb == 1) {
                $items = $t->fnb_name;
                $qty_package = $qtyFnb;
                $price = $t->fnb_price;
            }

            $amount = $t->items_amount;

            $row = [
                'is_table' => $t->is_table,
                'id_order_item' => $t->id_order_item,
                'items' => $items,
                'qty' => $qty_package,
                'price' => $price,
                'amount' => $amount,
            ];
            $ajax[] = $row;
        }

        return $ajax;
        // return response()->json(['data' => $ajax]);
    }

    public function addItemsFnB($id_order, Request $request)
    {
        $request->validate([
            'items' => 'required',
        ]);
        $orders = Orders::findOrFail($id_order);
        $items = $request->post('items');
        for ($i = 0; $i < count($items); $i++) {
            $fnb = FnBMenus::find($items[$i]);
            $fnbCekExist = OrderItems::where('id_order', $orders->id_order)
                ->where('id_fnb', $fnb->id_fnb)
                ->first();
            if (isset($fnbCekExist->id_fnb)) {
                $qty = $fnbCekExist->fnb_qty + 1;
                $amount = $qty * $fnb->fnb_price;

                $insert = OrderItems::where('id_order_item', $fnbCekExist->id_order_item)
                    ->update([
                        'fnb_qty' => $qty,
                        'fnb_amount' => $amount,
                        'items_amount' => $amount,
                    ]);
            } else {
                $amount = 1 * $fnb->fnb_price;
                $insert = OrderItems::create([
                    'id_order' => $orders->id_order,
                    'is_fnb' => 1,
                    'id_fnb' => $fnb->id_fnb,
                    'fnb_name' => $fnb->fnb_name,
                    'fnb_price' => $fnb->fnb_price,
                    'fnb_qty' => 1,
                    'fnb_amount' => $amount,
                    'items_amount' => $amount,
                ]);
            }
        }

        return response()->json($insert);
    }

    public function updateItemsFnB(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'fnb_qty' => 'required',
        ]);

        $id = $request->post('id');
        $qty = $request->post('fnb_qty');
        $data = OrderItems::findOrFail($id);
        $amount = $data->fnb_price * (int) $qty;

        $update = $data->update([
            'fnb_qty' => $qty,
            'fnb_amount' => $amount,
            'items_amount' => $amount,
        ]);

        return response()->json($update);
    }

    public function addItemsTables($id_order, Request $request)
    {
        $request->validate([
            'tables' => 'required',
        ]);

        $orders = Orders::findOrFail($id_order);
        $items = $request->post('tables');
        for ($i = 0; $i < count($items); $i++) {
            $table = Tables::find($items[$i]);

            $insert = OrderItems::create([
                'id_order' => $orders->id_order,
                'is_table' => 1,
                'id_table' => $table->id_table,
                'table_name' => $table->table_name,
            ]);
        }

        return response()->json($insert);
    }

    public function updateItemsTables(Request $request)
    {
        $request->validate([
            'package' => 'required',
        ]);

        $p = $request->post('package');
        $pExplode = explode('|', $p);

        $prType = $pExplode[0];
        $idItems = $pExplode[1];
        $idPrice = $pExplode[2];

        $data = OrderItems::findOrFail($idItems);
        if ($prType == 'pkg') {
            $prPackage = PricesPackages::find($idPrice);
            $update = $data->update([
                'id_pr_package' => $prPackage->id_pr_package,
                'package_name' => $prPackage->name,
                'package_price' => $prPackage->price,
                'package_time_limit' => $prPackage->time_limit,
                'items_amount' => $prPackage->price,
            ]);
        } else {
            $prOpenBill = PricesOpenBilling::find($idPrice);
            $update = $data->update([
                'id_pr_openbill' => $prOpenBill->id_pr_openbill,
                'openbill_name' => $prOpenBill->name,
                'openbill_price' => $prOpenBill->price,
                'amount' => 0,
            ]);
        }

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = OrderItems::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
