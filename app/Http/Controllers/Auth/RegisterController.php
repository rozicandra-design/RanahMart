<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // ── Pembeli ──────────────────────────────────────
    public function showPembeliForm()
    {
        return view('auth.register-pembeli');
    }

    public function registerPembeli(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pembeli',
        ]);

        Auth::login($user);
        return redirect()->route('pembeli.dashboard');
    }

    // ── UMKM Step 1 ───────────────────────────────────
    public function showUmkmStep1()
    {
        return view('auth.register-umkm-step1');
    }

    public function storeUmkmStep1(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        session(['umkm_step1' => $request->only('name', 'email', 'password')]);
        return redirect()->route('register.umkm.step2');
    }

    // ── UMKM Step 2 ───────────────────────────────────
    public function showUmkmStep2()
    {
        if (!session('umkm_step1')) return redirect()->route('register.umkm');
        return view('auth.register-umkm-step2');
    }

    public function storeUmkmStep2(Request $request)
    {
        $request->validate([
            'nama_toko'   => 'required|string|max:255',
            'kategori'    => 'required|string',
            'kecamatan'   => 'required|string',
            'alamat'      => 'required|string',
        ]);

        session(['umkm_step2' => $request->only('nama_toko', 'kategori', 'kecamatan', 'alamat')]);
        return redirect()->route('register.umkm.step3');
    }

    // ── UMKM Step 3 ───────────────────────────────────
    public function showUmkmStep3()
    {
        if (!session('umkm_step2')) return redirect()->route('register.umkm.step2');
        return view('auth.register-umkm-step3');
    }

    public function storeUmkmStep3(Request $request)
    {
        $request->validate([
            'no_hp'     => 'required|string|max:20',
            'nik'       => 'nullable|string|max:20',
        ]);

        $step1 = session('umkm_step1');
        $step2 = session('umkm_step2');

        $user = User::create([
            'name'     => $step1['name'],
            'email'    => $step1['email'],
            'password' => Hash::make($step1['password']),
            'role'     => 'penjual',
        ]);

        // Buat toko jika ada model Toko
        // Toko::create([...]);

        session()->forget(['umkm_step1', 'umkm_step2']);

        Auth::login($user);
        return redirect()->route('penjual.dashboard');
    }
}