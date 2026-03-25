<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;

class PembinaanController extends Controller
{
    public function index()   { return view('dinas.pembinaan.index'); }
public function create()  { return view('dinas.pembinaan.create'); }
    public function store()   { return back(); }
    public function show($id) { return view('dinas.pembinaan.show'); }
public function edit($id) { return view('dinas.pembinaan.edit'); }
    public function update()  { return back(); }
    public function destroy() { return back(); }

    public function daftarkanUmkm($program)
    {
        return back();
    }
}