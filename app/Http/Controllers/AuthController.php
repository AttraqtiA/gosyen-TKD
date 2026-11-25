<?php

namespace App\Http\Controllers;

use App\Enums\AccessRole;
use Illuminate\Http\Request;
use App\Models\AksesPin;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (AccessRole::current() !== null) {
            return redirect()->route('dashboard.index');
        }
        return view('dashboard.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        $akses = AksesPin::where('pin', $request->pin)->first();

        if ($akses && AccessRole::tryFrom($akses->role) !== null) {
            session([AccessRole::SESSION_KEY => $akses->role]);
            return redirect()->route('dashboard.index');
        }

        return redirect()->back()->with('error', 'PIN yang Anda masukkan salah!');
    }

    public function logout()
    {
        session()->forget(AccessRole::SESSION_KEY);
        return redirect()->route('login');
    }
}
