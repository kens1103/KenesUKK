<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm() {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // KEMBALI KE LOGINFORM SETELAH REGISTER
        return redirect('/')->with('openLogin', true)->with('success', 'Registrasi berhasil! Silahkan login!');
    }

    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect('/')->withInput()->with('openRegister', true)->with('error', 'Email tidak ditemukan. Silakan daftar terlebih dahulu.');
        }        

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('galeri')->with('success', 'Login berhasil!');
        }

        return redirect()->route('loginForm')->withInput()->with('error', 'Email atau password salah');

    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
