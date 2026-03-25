<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // TODO: ambil voucher milik user dari tabel voucher_users atau serupa
        return view('pembeli.voucher', compact('user'));
    }

    public function cek(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
        ]);

        // TODO: cek validitas voucher
        // Contoh response JSON untuk dipakai via fetch/axios di frontend
        return response()->json([
            'valid'   => false,
            'pesan'   => 'Voucher tidak ditemukan.',
            'diskon'  => 0,
        ]);
    }
}