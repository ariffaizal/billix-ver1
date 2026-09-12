<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\FnBCategory;
use App\Models\FnBMenus;
use Illuminate\Http\Request;

class FnBMenusController extends Controller
{
    public function index()
    {
        $title = 'Menu Food and Beverage';
        $data['category'] = FnBCategory::all();

        return view('settings.fnb_menu', compact(['title', 'data']));
    }

    public function data(Request $request)
    {
        $data = (new FnBMenus)->getMenu($request->category);
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_fnb;
            $row = [
                'no' => $no++,
                'category' => $t->category_name,
                'name' => $t->fnb_name,
                'price' => $t->fnb_price,
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

    public function available()
    {
        $data = (new FnBMenus)->getMenu('all');
        $ajax = [];
        foreach ($data as $d) {
            $ajax[] = [
                'category' => $d->category_name,
                'name' => '<input type="checkbox" name="items[]" id="b'.$d->id_fnb.'" value="'.$d->id_fnb.'" autocomplete="off"> '.$d->fnb_name,
                'price' => $d->fnb_price,
            ];
        }

        return response()->json(['data' => $ajax]);
    }

    public function show(Request $request)
    {
        $id = $request->post('id');
        $data = FnBMenus::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'price' => 'required',
            'category' => 'required',
        ]);

        $insert = FnBMenus::create([
            'id_fnbcategory' => $request->category,
            'fnb_name' => $request->menu_name,
            'fnb_price' => $request->price,
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'price' => 'required',
            'category' => 'required',
        ]);

        $id = $request->post('id');
        $data = FnBMenus::findOrFail($id);

        $update = $data->update([
            'id_fnbcategory' => $request->category,
            'fnb_name' => $request->menu_name,
            'fnb_price' => $request->price,
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = FnBMenus::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
