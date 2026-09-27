<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'id_order';

    protected $fillable = [
        'order_type',
        'order_time',
        'order_status',
        'cancel_time',
        'bill_name',
        'pay_method',
        'price_subtotal',
        'price_discount',
        'price_vat',
        'price_total',
        'cash_tendered',
        'cash_change',
        'created_by',
        'id_user_shift',
        'id_member',
        'member_name',
        'member_no',
        'has_refunded',
        'refund_order_id',
    ];

    /**
     * Relasi ke model OrderItems
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'id_order', 'id_order');
    }

    /**
     * Alias relasi items untuk kompatibilitas script CSV/Frontend
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'id_order', 'id_order');
    }

    public function getOrderByShift($id_user_shift)
    {
        return DB::table('orders', 'o')
            ->select([
                'o.*',
                'u.name',
            ])
            ->leftJoin('users as u', 'u.id', '=', 'o.created_by')
            // ->where('o.created_by', $id_user)
            // ->whereDate('o.created_at', '=', now())
            ->where('o.id_user_shift', $id_user_shift)
            ->orderByDesc('o.id_order')
            ->get();
    }

    public function getOrderByDateRange($startDate, $endDate)
    {
        $query = DB::table('orders', 'o')
            ->select([
                'o.*',
                'u.name',
            ])
            ->leftJoin('users as u', 'u.id', '=', 'o.created_by');

        if ($startDate == $endDate) {
            return $query->whereDate('o.created_at', '=', $startDate)
                ->orderByDesc('o.id_order')
                ->get();
        }

        return $query->whereBetween('o.created_at', [$startDate, $endDate])
            ->orderByDesc('o.id_order')
            ->get();
    }

    public function sumOrderByShift($id_user_shift)
    {
        return DB::table('orders')
            ->select([
                DB::raw('SUM(price_subtotal) as total_subtotal'),
                DB::raw('SUM(price_discount) as total_discount'),
                DB::raw('SUM(price_vat) as total_vat'),
                DB::raw('SUM(price_total) as total_price'),
            ])
            ->where('id_user_shift', $id_user_shift)
            ->where('order_status', 9)
            ->first();
    }

    public function getShiftSummary($id_user_shift): array
    {
        $shift = \App\Models\UserShift::find($id_user_shift);
        $orders = static::where('id_user_shift', $id_user_shift)->get();

        $cash = (float) $orders->where('order_status', 9)->where('pay_method', 'Cash')->sum('price_total');
        $transfer = (float) $orders->where('order_status', 9)->where('pay_method', 'Transfer')->sum('price_total');
        $qris = (float) $orders->where('order_status', 9)->where('pay_method', 'QRIS')->sum('price_total');
        $other = (float) $orders->where('order_status', 9)->where('pay_method', 'Other')->sum('price_total');
        $refund = (float) $orders->where('order_status', 7)->sum('price_total');

        $openingCash = (float) ($shift?->initial_capital ?? 0);
        $totalSales = $cash + $transfer + $qris + $other;
        $totalAmount = $openingCash + $totalSales;
        $netAmount = $totalAmount - $refund;

        $expectedCash = $openingCash + $cash - (float) ($shift?->cash_out ?? 0);
        $diff = (float) ($shift?->cash_actual ?? 0) - $expectedCash;

        return [
            'opening_cash' => $openingCash,
            'cash' => $cash,
            'transfer' => $transfer,
            'qris' => $qris,
            'other' => $other,
            'total_sales' => $totalSales,
            'total' => $totalAmount,
            'refund' => $refund,
            'net' => $netAmount,
            'expected_cash' => $expectedCash,
            'diff' => $diff,
        ];
    }

    public function sumOrderRefundByShift($id_user_shift)
    {
        return DB::table('orders')
            ->where('id_user_shift', $id_user_shift)
            ->where('order_status', 7)
            ->sum('price_total');
    }

    public function sumPayMethodByShift($pay_method, $id_user_shift)
    {
        return DB::table('orders')
            ->select([
                DB::raw('SUM(price_subtotal) as total_subtotal'),
                DB::raw('SUM(price_discount) as total_discount'),
                DB::raw('SUM(price_vat) as total_vat'),
                DB::raw('SUM(price_total) as total_price'),
            ])
            ->where('pay_method', $pay_method)
            ->where('id_user_shift', $id_user_shift)
            ->where('order_status', 9)
            ->first();
    }
}
