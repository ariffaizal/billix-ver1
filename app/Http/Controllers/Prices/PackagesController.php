<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Models\PricesPackages;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function index()
    {
        $title = 'Package List';

        return view('prices.packages', compact(['title']));
    }

    public function data()
    {
        $data = PricesPackages::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_pr_package;
            $row = [
                'no' => $no++,
                'name' => $t->name,
                'time_limit' => $t->time_limit,
                'price' => $t->price,
                'action' => '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i> Delete</button>
                </div>
                ',
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function show(Request $request)
    {
        $id = $request->post('id');
        $data = PricesPackages::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:100',
            'price' => 'required|numeric|max_digits:64',
            'time_limit' => 'required|date_format:H:i:s',
        ]);

        if ($request->post('time_limit') == '00:00:00') {
            return abort(500, 'time limit must be set!');
        }

        $insert = PricesPackages::create([
            'name' => $request->post('package_name'),
            'time_limit' => $request->post('time_limit'),
            'price' => $request->post('price'),
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:100',
            'time_limit' => 'required|date_format:H:i:s',
            'price' => 'required|numeric|max_digits:64',
        ]);

        if ($request->post('time_limit') == '00:00:00') {
            return abort(500, 'time limit must be set!');
        }

        $id = $request->post('id');
        $data = PricesPackages::findOrFail($id);

        $update = $data->update([
            'name' => $request->post('package_name'),
            'time_limit' => $request->post('time_limit'),
            'price' => $request->post('price'),
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = PricesPackages::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
