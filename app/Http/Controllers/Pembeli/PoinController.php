<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    public function index()
{
    $user      = auth()->user();
    $totalPoin = $user->poins()->sum('jumlah');
    $poins     = $user->poins()->latest()->get();

    return view('pembeli.poin', compact('totalPoin', 'poins'));
}
}