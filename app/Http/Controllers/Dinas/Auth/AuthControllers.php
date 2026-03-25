<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif atau telah diblokir.',
            ]);
        }

        $request->session()->regenerate();

        // Redirect berdasarkan role
        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'penjual' => redirect()->route('penjual.dashboard'),
            'dinas'   => redirect()->route('dinas.dashboard'),
            default   => redirect()->route('pembeli.dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Berhasil keluar.');
    }
}

class RegisterController extends Controller
{
    // ─── PEMBELI ───
    public function showPembeliForm()
    {
        return view('auth.register-pembeli');
    }

    public function registerPembeli(Request $request)
    {
        $data = $request->validate([
            'nama_depan'  => 'required|string|max:100',
            'nama_belakang' => 'required|string|max:100',
            'email'       => 'required|email|unique:users',
            'no_hp'       => 'required|string|max:20',
            'password'    => 'required|min:8|confirmed',
            'kecamatan'   => 'nullable|string',
            'setuju'      => 'accepted',
        ], [
            'email.unique'    => 'Email sudah terdaftar.',
            'password.min'    => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'setuju.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        $user = User::create([
            'nama_depan'   => $data['nama_depan'],
            'nama_belakang'=> $data['nama_belakang'],
            'email'        => $data['email'],
            'no_hp'        => $data['no_hp'],
            'password'     => Hash::make($data['password']),
            'role'         => 'pembeli',
            'kecamatan'    => $data['kecamatan'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('pembeli.dashboard')
            ->with('success', 'Selamat datang di RanahMart, ' . $user->nama_depan . '!');
    }

    // ─── UMKM STEP 1: Akun ───
    public function showUmkmStep1()
    {
        return view('auth.register-umkm-step1');
    }

    public function storeUmkmStep1(Request $request)
    {
        $data = $request->validate([
            'nama_depan'   => 'required|string|max:100',
            'nama_belakang'=> 'required|string|max:100',
            'email'        => 'required|email|unique:users',
            'no_hp'        => 'required|string|max:20',
            'password'     => 'required|min:8|confirmed',
        ]);

        session(['umkm_step1' => $data]);

        return redirect()->route('register.umkm.step2');
    }

    // ─── UMKM STEP 2: Data Usaha ───
    public function showUmkmStep2()
    {
        if (!session('umkm_step1')) {
            return redirect()->route('register.umkm');
        }
        return view('auth.register-umkm-step2');
    }

    public function storeUmkmStep2(Request $request)
    {
        $data = $request->validate([
            'nama_toko'   => 'required|string|max:150',
            'kategori'    => 'required|in:makanan_minuman,fashion,kerajinan,herbal_kesehatan,seni_budaya,kecantikan,lainnya',
            'kecamatan'   => 'required|string',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:' . date('Y'),
            'alamat_lengkap'=> 'required|string',
            'deskripsi'   => 'nullable|string',
            'kisaran_omzet'=> 'nullable|string',
        ]);

        session(['umkm_step2' => $data]);

        return redirect()->route('register.umkm.step3');
    }

    // ─── UMKM STEP 3: Dokumen ───
    public function showUmkmStep3()
    {
        if (!session('umkm_step2')) {
            return redirect()->route('register.umkm.step2');
        }
        return view('auth.register-umkm-step3');
    }

    public function storeUmkmStep3(Request $request)
    {
        $request->validate([
            'foto_ktp'    => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'foto_produk' => 'required|array|min:1',
            'foto_produk.*' => 'image|mimes:jpg,jpeg,png|max:5120',
            'bank'        => 'required|string',
            'no_rekening' => 'required|string|max:30',
            'setuju'      => 'accepted',
        ]);

        $step1 = session('umkm_step1');
        $step2 = session('umkm_step2');

        // Buat user
        $user = User::create([
            'nama_depan'   => $step1['nama_depan'],
            'nama_belakang'=> $step1['nama_belakang'],
            'email'        => $step1['email'],
            'no_hp'        => $step1['no_hp'],
            'password'     => Hash::make($step1['password']),
            'role'         => 'penjual',
        ]);

        // Upload KTP
        $fotoKtp = $request->file('foto_ktp')->store('ktp', 'public');

        // Buat toko
        $toko = Toko::create([
            'user_id'       => $user->id,
            'nama_toko'     => $step2['nama_toko'],
            'slug'          => Str::slug($step2['nama_toko']) . '-' . $user->id,
            'kategori'      => $step2['kategori'],
            'kecamatan'     => $step2['kecamatan'],
            'alamat_lengkap'=> $step2['alamat_lengkap'],
            'deskripsi'     => $step2['deskripsi'] ?? null,
            'foto_ktp'      => $fotoKtp,
            'bank'          => $request->bank,
            'no_rekening'   => $request->no_rekening,
            'atas_nama_rekening' => $user->nama_lengkap,
            'status'        => 'pending',
        ]);

        // Bersihkan session
        session()->forget(['umkm_step1', 'umkm_step2']);

        Auth::login($user);

        return redirect()->route('penjual.dashboard')
            ->with('success', 'Pendaftaran UMKM berhasil! Menunggu verifikasi admin & Dinas (3–5 hari kerja).');
    }
}
