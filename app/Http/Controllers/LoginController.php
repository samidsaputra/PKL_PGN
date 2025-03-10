<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
            'email.email' => 'Format email tidak valid',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $role = Auth::user()->role;
            session(['role' => $role]);
            return redirect()->route('dashboard');
        }

        return redirect('/')
            ->withErrors('Email atau password salah')
            ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/')->with('success', 'Anda berhasil logout');
    }
}