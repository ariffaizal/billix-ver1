<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'title' => 'Profile',
            'user' => $request->user(),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $id = $request->post('id');
        $data = User::findOrFail($id);
        $update = $data->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json($update);
    }
}
