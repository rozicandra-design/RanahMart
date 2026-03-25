<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;

class PengumumanController extends Controller
{
    public function index()   { return view('dinas.pengumuman'); }
    public function create()  { return view('dinas.pengumuman'); }
    public function store()   { return back(); }
    public function show($id) { return view('dinas.pengumuman'); }
    public function edit($id) { return view('dinas.pengumuman'); }
    public function update()  { return back(); }
    public function destroy() { return back(); }
}