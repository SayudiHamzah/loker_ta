<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{


    // Menampilkan Form Login
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // dd($request);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('loker')->with('success', 'Login Berhasil');

        }

        return back()->with('error', 'Email atau Password Salah');
    }

    // Proses Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
