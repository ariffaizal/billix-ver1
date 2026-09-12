<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\FnBCategory;
use Illuminate\Http\Request;

class FnBCategoryController extends Controller
{
    public function index()
    {
        $title = 'Category Food and Beverage';

        return view('settings.fnb_category', compact(['title']));
    }

    public function data()
    {
        $data = FnBCategory::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_fnbcategory;
            $row = [
                'no' => $no++,
                'name' => $t->category_name,
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
        $data = FnBCategory::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $insert = FnBCategory::create([
            'category_name' => $request->category_name,
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
        ]);

        $id = $request->post('id');
        $data = FnBCategory::findOrFail($id);

        $update = $data->update([
            'category_name' => $request->category_name,
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = FnBCategory::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
