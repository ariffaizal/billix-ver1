<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\User;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftReportController extends Controller
{
    public function index()
    {
        $title = 'Shift Report';

        return view('reports.by_shift', compact(['title']));
    }

    public function data(Request $request)
{
    $start_date_raw = $request->get('start_date');
    $end_date_raw = $request->get('end_date');

    // Pastikan format tanggal disesuaikan ke Y-m-d (MySQL Format)
    $start_date = $start_date_raw ? Carbon::parse($start_date_raw)->format('Y-m-d') : null;
    $end_date = $end_date_raw ? Carbon::parse($end_date_raw)->format('Y-m-d') : null;

    $query = UserShift::with(['user', 'orders'])
        ->orderByDesc('shift_start');

    if ($start_date && $end_date) {
        if ($start_date === $end_date) {
            $query->whereDate('shift_start', $start_date);
        } else {
            $query->whereDate('shift_start', '>=', $start_date)
                  ->whereDate('shift_start', '<=', $end_date);
        }
    }

    $data = $query->get();

    $ajax = [];
    foreach ($data as $t) {
        $id = $t->id_user_shift;
        $openingCash = $t->initial_capital;

        // Ambil koleksi orders agar aman dari null
        $orders = $t->orders ?? collect();

        $orderPayCash = $orders->where('order_status', 9)
            ->where('pay_method', 'Cash')
            ->sum('price_total');
        $orderPayTransfer = $orders->where('order_status', 9)
            ->where('pay_method', 'Transfer')
            ->sum('price_total');
        $orderPayQRIS = $orders->where('order_status', 9)
            ->where('pay_method', 'QRIS')
            ->sum('price_total');
        $orderPayOther = $orders->where('order_status', 9)
            ->where('pay_method', 'Other')
            ->sum('price_total');
        $orderRefund = $orders->where('order_status', 7)->sum('price_total');

        $sumOrderPayment = $orderPayCash + $orderPayTransfer + $orderPayQRIS + $orderPayOther;
        $totalAmount = $openingCash + $sumOrderPayment;
        $netAmount = $totalAmount - $orderRefund;

        $row = [
            'id' => $id,
            'operator' => $t->shift_info ?? '-',
            'opening_time' => $t->shift_start,
            'closed_time' => $t->shift_end ?? '-',
            'opening_cash' => $openingCash,
            'cash' => $orderPayCash,
            'transfer' => $orderPayTransfer,
            'qris' => $orderPayQRIS,
            'other' => $orderPayOther,
            'total' => $totalAmount,
            'refund' => $orderRefund,
            'net' => $netAmount,
            'action' => '<a href="'.url('reports/byshift/print?id='.$id).'" target="_blank" class="btn btn-sm btn-outline-primary" title="Print this report"><i class="bi bi-printer"></i> Print</a>',
        ];
        $ajax[] = $row;
    }

    return response()->json(['data' => $ajax]);
}

    public function close(Request $request)
{
    $id = $request->get('id');
    $title = 'Closing Shift #' . $id;
    $data['shift'] = UserShift::find($id);
    $data['user'] = User::find($data['shift']->id_user);
    
    $data['Cash'] = (new Orders)->sumPayMethodByShift('Cash', $id);
    $data['Transfer'] = (new Orders)->sumPayMethodByShift('Transfer', $id);
    $data['QRIS'] = (new Orders)->sumPayMethodByShift('QRIS', $id);
    $data['Other'] = (new Orders)->sumPayMethodByShift('Other', $id);

    // Hitung total kas yang diharapkan (Initial Capital + Total Cash Orders - Cash Out)
    $cashSales = $data['Cash']->total_price ?? 0;
    $cashOut = $data['shift']->cash_out ?? 0;
    
    $data['expected'] = ($data['shift']->initial_capital + $cashSales) - $cashOut;
    
    // Hitung selisih antara kas aktual dan kas yang diharapkan
    $cashActual = $data['shift']->cash_actual ?? 0;
    $data['diff'] = $cashActual - $data['expected'];

    return view('reports.close_shift', compact(['title', 'data']));
}

    public function print(Request $request)
{
    $title = 'Shift Report';
    $id = $request->get('id');

    // Tambahkan relasi orders menggunakan with('orders')
    $data['shift'] = UserShift::with('orders')->find($id);

    return view('reports.print_shift_v2', compact(['title', 'data']));
}

    // Melihat detail transaksi pada shift tertentu
public function details(Request $request)
{
    $id = $request->get('id');

    // Ubah 'orders.items' menjadi 'orders.orderItems'
    $shift = UserShift::with(['orders.orderItems' => function ($query) {
        $query->orderBy('created_at', 'desc');
    }])->findOrFail($id);

    return response()->json($shift->orders);
}
}
