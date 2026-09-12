<?php

namespace App\Http\Controllers\Prices;

use App\Http\Controllers\Controller;
use App\Models\PricesOpenBilling;
use Illuminate\Http\Request;

class OpenBillingController extends Controller
{
    public function index()
    {
        $title = 'Open Billing Price';

        return view('prices.open_billing', compact(['title']));
    }

    public function data()
    {
        $data = PricesOpenBilling::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_pr_openbill;
            $row = [
                'no' => $no++,
                'name' => $t->name,
                'price' => $t->price,
                'action' => '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i> Delete</button>
                </div',
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function show(Request $request)
    {
        $id = $request->post('id');
        $data = PricesOpenBilling::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'price_name' => 'required|string|max:100',
            'price' => 'required|numeric|max_digits:64',
        ]);

        $insert = PricesOpenBilling::create([
            'name' => $request->post('price_name'),
            'price' => $request->post('price'),
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'price_name' => 'required|string|max:100',
            'price' => 'required|numeric|max_digits:64',
        ]);

        $id = $request->post('id');
        $data = PricesOpenBilling::findOrFail($id);

        $update = $data->update([
            'name' => $request->post('price_name'),
            'price' => $request->post('price'),
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = PricesOpenBilling::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
