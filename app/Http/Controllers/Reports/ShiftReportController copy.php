<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\User;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftReportController extends Controller
{
    public function index()
    {
        $title = 'Shift Report';

        return view('reports.by_shift', compact(['title']));
    }

    public function data(Request $request)
    {
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $query = DB::table('user_shift', 's')
            ->select([
                's.*',
                'u.name',
            ])
            ->leftJoin('users as u', 'u.id', '=', 's.id_user')
            ->orderByDesc('s.shift_start');
        if ($start_date == $end_date) {
            $query->whereDate('s.shift_start', '=', $start_date);
        } else {
            $query->whereBetween('s.shift_start', [$start_date, $end_date]);
        }
        $data = $query->get();

        $ajax = [];
        foreach ($data as $t) {
            $id = $t->id_user_shift;
            $sumCash = (new Orders)->sumPayMethodByShift('cash', $id);
            $expected = ($t->initial_capital + $sumCash->total_price) - $t->cash_out;
            $row = [
                'id' => $id,
                'opened_by' => $t->name,
                'opening_time' => $t->shift_start,
                'closed_time' => $t->shift_end,
                'expected_cash' => $expected,
                'actual_cash' => $t->cash_actual,
                'diff' => $t->cash_actual - $expected,
                'action' => '<a href="'.url('reports/byshift/print?id='.$id).'" target="_blank" class="btn btn-sm btn-outline-primary" title="Print this report"><i class="bi bi-printer"></i> Print</a>',
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function close(Request $request)
    {
        $id = $request->get('id');
        $title = 'Closing Shift';
        $data['shift'] = UserShift::find($id);
        $data['user'] = User::find($data['shift']->id_user);
        $sumCash = (new Orders)->sumPayMethodByShift('cash', $id);
        $data['expected'] = ($data['shift']->initial_capital + $sumCash->total_price) - $data['shift']->cash_out;
        $data['diff'] = $data['shift']->cash_actual - $data['expected'];

        return view('reports.close_shift', compact(['title', 'data']));
    }

    public function print(Request $request)
    {
        $title = 'Shift Report';
        $id = $request->get('id');
        $data['shift'] = UserShift::find($id);
        $data['sum'] = (new Orders)->sumOrderByShift($id);
        $data['sumRefund'] = (new Orders)->sumOrderRefundByShift($id);
        $data['sumCash'] = (new Orders)->sumPayMethodByShift('cash', $id);

        return view('reports.print_shift', compact(['title', 'data']));
    }
}
