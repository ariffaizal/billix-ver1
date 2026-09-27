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
        $summary = (new Orders)->getShiftSummary($id);

        $row = [
            'id' => $id,
            'operator' => $t->shift_info ?? '-',
            'opening_time' => $t->shift_start,
            'closed_time' => $t->shift_end ?? '-',
            'opening_cash' => $summary['opening_cash'],
            'cash' => $summary['cash'],
            'transfer' => $summary['transfer'],
            'qris' => $summary['qris'],
            'other' => $summary['other'],
            'total' => $summary['total'],
            'refund' => $summary['refund'],
            'net' => $summary['net'],
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

    $summary = (new Orders)->getShiftSummary($id);
    $data['Cash'] = (object) ['total_price' => $summary['cash']];
    $data['Transfer'] = (object) ['total_price' => $summary['transfer']];
    $data['QRIS'] = (object) ['total_price' => $summary['qris']];
    $data['Other'] = (object) ['total_price' => $summary['other']];
    $data['summary'] = $summary;
    $data['expected'] = $summary['expected_cash'];
    $data['diff'] = $summary['diff'];

    return view('reports.close_shift', compact(['title', 'data']));
}

    public function print(Request $request)
{
    $title = 'Shift Report';
    $id = $request->get('id');

    $data['shift'] = UserShift::with('orders')->find($id);
    $data['summary'] = (new Orders)->getShiftSummary($id);

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
