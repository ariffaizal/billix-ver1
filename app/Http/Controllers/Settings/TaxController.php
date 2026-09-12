<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $title = 'Tax Rate';

        return view('settings.tax', compact('title'));
    }

    public function data()
    {
        $data = Tax::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->tax_id;
            $row = [
                'no' => $no++,
                'rate' => $t->rate,
                'remarks' => $t->remarks,
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
        $data = Tax::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'rate' => 'required|string|max:255',
        ]);

        $insert = Tax::create([
            'rate' => $request->rate,
            'remarks' => $request->remarks,
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'rate' => 'required|string|max:255',
        ]);

        $id = $request->post('id');
        $data = Tax::findOrFail($id);

        $update = $data->update([
            'rate' => $request->rate,
            'remarks' => $request->remarks,
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = Tax::findOrFail($id)->delete();

        return response()->json($delete);
    }

    // Menampilkan list member di menu payment
    // public function view()
    // {
    //     $data_member = Tax::all();
    //     return view('orders.payment', compact('data_member'));
    // }

}
