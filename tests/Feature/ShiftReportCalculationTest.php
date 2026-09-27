<?php

use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\UserShift;

it('uses one consistent shift summary for by-shift and close-shift reports', function () {
    $shift = UserShift::create([
        'id_user' => 1,
        'shift_start' => now(),
        'initial_capital' => 100000,
        'cash_actual' => 250000,
        'cash_out' => 3000,
        'shift_info' => 'Operator 1',
        'shift_active' => 0,
    ]);

    $cashOrder = Orders::create([
        'order_type' => 'fnb_only',
        'order_time' => now(),
        'order_status' => 9,
        'pay_method' => 'Cash',
        'price_subtotal' => 90000,
        'price_discount' => 5000,
        'price_vat' => 10000,
        'price_total' => 95000,
        'id_user_shift' => $shift->id_user_shift,
        'created_by' => 1,
    ]);

    $transferOrder = Orders::create([
        'order_type' => 'open_bill',
        'order_time' => now(),
        'order_status' => 9,
        'pay_method' => 'Transfer',
        'price_subtotal' => 60000,
        'price_discount' => 0,
        'price_vat' => 6000,
        'price_total' => 66000,
        'id_user_shift' => $shift->id_user_shift,
        'created_by' => 1,
    ]);

    $refundOrder = Orders::create([
        'order_type' => 'fnb_only',
        'order_time' => now(),
        'order_status' => 7,
        'pay_method' => 'Cash',
        'price_subtotal' => 15000,
        'price_discount' => 0,
        'price_vat' => 0,
        'price_total' => 15000,
        'id_user_shift' => $shift->id_user_shift,
        'created_by' => 1,
    ]);

    OrderItems::create([
        'id_order' => $cashOrder->id_order,
        'is_fnb' => 1,
        'items_amount' => 90000,
        'created_by' => 1,
    ]);

    OrderItems::create([
        'id_order' => $transferOrder->id_order,
        'is_table' => 1,
        'items_amount' => 60000,
        'created_by' => 1,
    ]);

    $summary = (new Orders)->getShiftSummary($shift->id_user_shift);

    expect($summary)->toMatchArray([
        'cash' => 95000,
        'transfer' => 66000,
        'qris' => 0,
        'other' => 0,
        'refund' => 15000,
        'total' => 261000,
        'net' => 246000,
        'expected_cash' => 197000,
        'diff' => 53000,
    ]);
});
