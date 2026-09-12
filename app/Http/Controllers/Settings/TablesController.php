<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\TableActive;
use App\Models\Tables;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    public function index()
    {
        $title = 'Table List';

        return view('settings.table', compact(['title']));
    }

    public function data()
    {
        $data = Tables::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_table;
            $testing = '';
            $action = '';
            if (TableActive::where('id_table', $id)->doesntExist()) {
                $testing = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-success btnTestRelay" id="btnOn"  data-id="'.$id.'" data-state="on">ON</button>
                    <button type="button" class="btn btn-sm btn-secondary btnTestRelay" id="btnOff" data-id="'.$id.'" data-state="off">OFF</button>
                </div>';
                $action = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i> Delete</button>
                </div>
                ';
            } else {
                $testing = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-success btnTestRelay" id="btnOn"  data-id="'.$id.'" data-state="on">ON</button>
                   
                </div>';
                $action = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                </div>
                ';
            }

            $row = [
                'no' => $no++,
                'name' => $t->table_name,
                'active' => ($t->is_active == 1) ? 'Yes' : 'No',
                'relay' => $t->http_relay,
                'testing' => $testing,
                'action' => $action,

            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function available(Request $request)
    {
        $id_order = $request->get('id_order');
        $ajax = [];
        $data = (new Tables)->getTableAvailable($id_order);
        foreach ($data as $d) {
            $ajax[] = [
                'tables' => '
                <input type="checkbox" class="btn-check" name="tables[]" id="b'.$d->id_table.'" value="'.$d->id_table.'" autocomplete="off">
                <label class="btn btn-lg btn-block btn-outline-success" for="b'.$d->id_table.'">'.$d->table_name.'</label>
                ',
            ];
        }

        return response()->json(['data' => $ajax]);
    }

    public function showAvailable(Request $request)
    {
        $id_order = $request->get('id_order');
        $data = (new Tables)->getTableAvailable($id_order);

        return response()->json($data);
    }

    public function showAllTableStatus()
    {
        $data = (new Tables)->getAllTableWithStatus();

        return response()->json($data);
    }

    public function show(Request $request)
    {
        $id = $request->post('id');
        $data = Tables::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'table_name' => ['required', 'string', 'max:255'],
        ]);

        $insert = Tables::create([
            'table_name' => $request->post('table_name'),
            'is_active' => $request->post('is_active'),
            'http_relay' => $request->post('relay'),
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'table_name' => ['required', 'string', 'max:255'],
        ]);

        $id = $request->post('id');
        $is_active = $request->post('is_active');
        $data = Tables::findOrFail($id);
        $update = $data->update([
            'table_name' => $request->post('table_name'),
            'is_active' => ($is_active) ? 1 : 0,
            'http_relay' => $request->post('relay'),
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = Tables::findOrFail($id)->delete();

        return response()->json($delete);
    }
}
