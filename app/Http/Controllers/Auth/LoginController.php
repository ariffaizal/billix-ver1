<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View
    {
        $title = 'Login';

        return view('auth.login2', compact(['title', 'data']));
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->is_active != 1) {
                return back()->with([
                    'loginError' => 'Akun Disable!, hubungi Administrator',
                ]);
            }
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->with([
            'loginError' => 'Username atau password salah!',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function gantitahun(Request $request): void
    {
        $request->validate([
            'tahun' => 'required|numeric',
        ]);
        session()->forget('ses_tahun');
        session()->put('ses_tahun', $request->post('tahun'));
    }
}
