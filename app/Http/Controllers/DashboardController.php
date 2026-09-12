<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        // Data Revenue
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek(1); //mulai dari hari senin
        $startOfMonth = Carbon::now()->startOfMonth();
        $data['revenueToday'] = Orders::where('order_status', 9)->whereDate('created_at', $today)->sum('price_total');
        $data['refundToday'] = Orders::where('order_status', 7)->whereDate('created_at', $today)->sum('price_total');
        $data['revenueLast7Days'] = Orders::where('order_status', 9)->whereBetween('created_at', [$startOfWeek, $today->endOfDay()])->sum('price_total');
        $data['revenueThisMonth'] = Orders::where('order_status', 9)->whereBetween('created_at', [$startOfMonth, $today->endOfDay()])->sum('price_total');

        // Data Order
        $data['ototal'] = Orders::count();
        $data['odraft'] = Orders::where('order_status', 0)->count();
        $data['opending'] = Orders::whereIn('order_status', [1, 5])->count();
        $data['opstart'] = Orders::where('order_status', 2)->count();
        $data['ofinish'] = Orders::whereIn('order_status', [3, 9])->count();

        return view('mainapp.dashboard', compact(['title', 'data']));
    }
}
