<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftServiceController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'initial_capital' => 'required',
        ]);

        $insert = UserShift::create([
            'id_user' => Auth::id(),
            'shift_start' => now(),
            'initial_capital' => $request->post('initial_capital'),
            'shift_info' => $request->post('information'),
            'shift_active' => 1,
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_shift' => 'required',
            'actual_cash' => 'required',
            'cash_out' => 'required',
        ]);

        if ($request->cash_out > 0) {
            $request->validate(['cash_out_info' => 'required']);
        }

        $id = $request->post('id_shift');
        $data = UserShift::findOrFail($id);

        $update = $data->update([
            'shift_end' => now(),
            'shift_active' => 0,
            'cash_actual' => $request->actual_cash,
            'cash_out' => $request->cash_out,
            'cash_out_info' => $request->cash_out_info,
        ]);

        return response()->json($data);
    }
}
