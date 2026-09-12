<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $title = 'User List';
        $data['role'] = Role::all();

        return view('settings.user', compact(['title', 'data']));
    }

    public function data()
    {
        $data = User::all();
        $ajax = [];
        $no = 1;
        foreach ($data as $t) {
            $id = $t->id;

            $action = '';
            if ($id == 1) {
                $action = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                </div>';
            } else {
                $action = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info" id="btnEdit"  data-id="'.$id.'"><i class="bi bi-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete" data-id="'.$id.'"><i class="bi bi-trash"></i> Delete</button>
                </div>';
            }
            $row = [
                'no' => $no++,
                'nama' => $t->name,
                'username' => $t->username,
                'role' => $t->role,
                'action' => $action,
            ];
            $ajax[] = $row;
        }

        return response()->json(['data' => $ajax]);
    }

    public function show(Request $request)
    {
        $id = $request->post('id');
        $data = User::findOrFail($id);

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:100', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:100'],
        ]);

        if (! empty($request->password)) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        $id = $request->post('id');
        $data = User::findOrFail($id);

        $update = $data->update([
            'name' => $request->name,
            'role' => $request->role,
        ]);

        if (! empty($request->password)) {

            $update = $data->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json($update);
    }

    public function delete(Request $request)
    {
        $id = $request->post('id');
        $delete = User::findOrFail($id)->delete();
        return response()->json($delete);
    }
}
