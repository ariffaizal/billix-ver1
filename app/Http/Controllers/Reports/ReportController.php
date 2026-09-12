<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function byShift()
    {
        $title = 'Shift Report';
        $data = DB::table('user_shift', 's')
            ->select([
                's.*',
                'u.name',
            ])
            ->leftJoin('users as u', 'u.id', '=', 's.id_user')
            ->orderByDesc('s.shift_start')
            ->get();

        return view('reports.by_shift', compact(['title', 'data']));
    }

    public function show($id)
    {
        // Code to display a specific report
    }

    public function create()
    {
        // Code to show form for creating a new report
    }

    public function store(Request $request)
    {
        // Code to store a new report
    }

    public function edit($id)
    {
        // Code to show form for editing a report
    }

    public function update(Request $request, $id)
    {
        // Code to update a report
    }

    public function destroy($id)
    {
        // Code to delete a report
    }
}
