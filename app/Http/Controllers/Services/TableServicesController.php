<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\TableActive;
use App\Models\Tables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\Facades\MQTT;

class TableServicesController extends Controller
{
    private function sendCommand($command)
    {
        $topic = env('MQTT_TOPIC');

        return MQTT::publish($topic, $command);
    }

    public function testRelay(Request $request)
    {
        $request->validate([
            'id_table' => 'required',
            'state' => 'required',
        ]);

        $id_table = $request->id_table;
        $state = $request->state;

        $table = Tables::find($id_table);
        $relay = $table->http_relay;

        $command = $relay.'?state='.$state;
        $send = $this->sendCommand($command);

        return response()->json(['message' => $send]);
    }

    public function startOrderTable(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
        ]);

        $order = Orders::find($request->id_order);
        if ($order->order_type == 'fnb_only') {
            return response()->json(['message' => 'Order is not table'], 500);
        }

        $listTable = OrderItems::where('id_order', $order->id_order)
            ->where('is_table', 1);

        $time_start = now();

        foreach ($listTable->get() as $i) {
            if ($order->order_type == 'open_bill') {
                TableActive::updateOrCreate(
                    [
                        'id_table' => $i->id_table,
                    ],
                    [
                        'id_order' => $i->id_order,
                        'is_openbill' => 1,
                        'time_start' => $time_start,
                        'is_active' => 1,
                        'is_started' => 1,
                    ]
                );
            } else {
                TableActive::where('id_table', $i->id_table)
                    ->where('id_order', $i->id_order)
                    ->update(
                        [
                            'is_active' => 1,
                            'is_started' => 1,
                            'time_start' => $time_start,
                        ]
                    );
            }

            $table = Tables::find($i->id_table);
            $relay = $table->http_relay;
            $command = $relay.'?state=on';
            $this->sendCommand($command);
        }

        if ($order->order_type == 'open_bill') {
            $listTable->update(['openbill_time_start' => $time_start]);
        }

        $update = $order->update(['order_status' => 3]);

        return response()->json(['message' => $update]);
    }

    public function stopOrderTable(Request $request)
    {
        $request->validate([
            'id_order' => 'required',
        ]);

        $order = Orders::find($request->id_order);

        if ($order->order_type != 'open_bill') {
            return response()->json(['message' => 'Order is not Open Billing'], 500);
        }

        $tableList = OrderItems::where('id_order', $order->id_order)
            ->where('is_table', 1)
            ->get();

        $time_stop = Carbon::now();

        foreach ($tableList as $i) {
            $start_time = $i->openbill_time_start;
            $time_start = Carbon::parse($start_time);
            $time_total = (int) $time_start->diffInMinutes($time_stop);
            $totalPrice = (int) $i->openbill_price * $time_total;

            OrderItems::find($i->id_order_item)->update(
                [
                    'openbill_totaltime' => $time_total,
                    'openbill_totalprice' => $totalPrice,
                    'items_amount' => $totalPrice,
                ]
            );

            $table = Tables::find($i->id_table);
            $relay = $table->http_relay;
            $command = $relay.'?state=off';
            $this->sendCommand($command);
        }

        TableActive::where('id_order', $order->id_order)->delete();

        $update = $order->update(['order_status' => 4]);

        return response()->json(['message' => $update]);
    }

    public function changeTableOnSession(Request $request)
    {
        $request->validate([
            'new_table' => 'required',
        ]);

        $explode = explode('|', $request->new_table);
        $id_order_item = $explode[0];
        $new_id_table = $explode[1];

        $orderItem = OrderItems::find($id_order_item);
        $oldTable = Tables::find($orderItem->id_table);
        $relay = $oldTable->http_relay;
        $command = $relay.'?state=off';
        $this->sendCommand($command);

        $newTable = Tables::find($new_id_table);

        DB::transaction(function () use ($orderItem, $newTable) {
            TableActive::where('id_order', $orderItem->id_order)
                ->where('id_table', $orderItem->id_table)
                ->update([
                    'id_table' => $newTable->id_table,
                ]);

            $orderItem->update([
                'id_table' => $newTable->id_table,
                'table_name' => $newTable->table_name,
            ]);
        });

        $relayNew = $newTable->http_relay;
        $command = $relayNew.'?state=on';
        $this->sendCommand($command);

        return response()->json(['message' => 'Transfer table success']);
    }
}
