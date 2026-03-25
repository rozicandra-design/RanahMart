<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('pembeli.profil', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('pembeli.profil', compact('user'));
    }

   public function update(Request $request)
{
    $user = auth()->user();

    $data = $request->validate([
        'nama_depan'    => 'required|string|max:100',
        'nama_belakang' => 'nullable|string|max:100',
        'no_hp'         => 'nullable|string|max:20',
        'tanggal_lahir' => 'nullable|date',
        'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
        'kota'          => 'nullable|string|max:100',
        'foto_profil'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($request->hasFile('foto_profil')) {
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }
        $data['foto_profil'] = $request->file('foto_profil')
            ->store('profil', 'public');
    }

    $user->update($data);

    return back()->with('success', 'Profil berhasil diperbarui.');
}

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}