<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $title = 'Store Settings';
        $data = Store::find(1);

        return view('settings.store', compact(['title', 'data']));
    }

    public function create(Request $request)
    {
        $request->validate([
            'store_name' => 'required',
        ]);

        $data = Store::find(1);
        $update = $data->update([
            'store_name' => $request->store_name,
            'store_desc' => $request->description,
            'store_address_1' => $request->address,
            'store_phone' => $request->phone_number,
            'store_email' => $request->email,
        ]);

        return response()->json($update);
    }
}
