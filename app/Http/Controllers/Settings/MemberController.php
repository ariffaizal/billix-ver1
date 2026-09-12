<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $title = 'Member List';

        return view('settings.member', compact('title'));
    }

    public function data()
    {
        $data = Member::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id_member;
            $row = [
                'no' => $no++,
                'name' => $t->member_name,
                'phone' => $t->member_no,
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
        $data = Member::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_id' => 'required',
        ]);

        $insert = Member::create([
            'member_name' => $request->name,
            'member_no' => $request->member_id,
        ]);

        return response()->json($insert);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_id' => 'required',
        ]);

        $id = $request->post('id');
        $data = Member::findOrFail($id);

        $update = $data->update([
            'member_name' => $request->name,
            'member_no' => $request->member_id,
        ]);

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = Member::findOrFail($id)->delete();

        return response()->json($delete);
    }

    // Menampilkan list member di menu payment
    public function view()
    {
        $data_member = Member::all();

        return view('orders.payment', compact('data_member'));
    }
}
