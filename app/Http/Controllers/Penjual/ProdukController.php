<?php
namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Lengkapi profil toko terlebih dahulu.');
        }

        $query = Produk::where('toko_id', $toko->id)->latest();

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $produks = $query->paginate(15)->withQueryString();

        return view('penjual.produk.index', compact('produks', 'toko'));
    }

    public function create()
    {
        $toko = auth()->user()->toko;
        return view('penjual.produk.create', compact('toko'));
    }

    public function store(Request $request)
{
    $toko = auth()->user()->toko;

    $data = $request->validate([
        'nama'          => 'required|string|max:200',
        'deskripsi'     => 'nullable|string',
        'harga'         => 'required|numeric|min:0',
        'harga_coret'   => 'nullable|numeric|min:0',
        'stok'          => 'required|integer|min:0',
        'kategori'      => 'required|string',
        'sub_kategori'  => 'nullable|string',
        'berat'         => 'nullable|integer|min:0',
        'sku'           => 'nullable|string|max:100',
        'foto_produk'   => 'required|array|min:1|max:5',
        'foto_produk.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    // Simpan foto pertama sebagai foto utama
    $foto = $request->file('foto_produk')[0]->store("produk/{$toko->id}", 'public');

    Produk::create([
    'toko_id'      => $toko->id,
    'user_id'      => auth()->id(),   // ← tambah ini
    'nama'         => $data['nama'],
    'deskripsi'    => $data['deskripsi'] ?? null,
    'harga'        => $data['harga'],
    'harga_coret'  => $data['harga_coret'] ?? null,
    'stok'         => $data['stok'],
    'kategori'     => $data['kategori'],
    'berat'        => $data['berat'] ?? null,
    'status'       => 'nonaktif',
    'foto'         => $foto,
    'slug'         => Str::slug($data['nama']) . '-' . Str::random(5),
]);

    return redirect()->route('penjual.produk.index')
        ->with('success', 'Produk berhasil dikirim untuk review.');
}

    public function edit(Produk $produk)
    {
        $this->authorizeProduk($produk);
        $toko = auth()->user()->toko;
        return view('penjual.produk.edit', compact('produk', 'toko'));
    }

    public function update(Request $request, Produk $produk)
    {
        $this->authorizeProduk($produk);

        $data = $request->validate([
            'nama'        => 'required|string|max:200',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'harga_coret' => 'nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'kategori'    => 'required|string',
            'berat'       => 'nullable|integer|min:0',
            'status'      => 'required|in:aktif,nonaktif,habis',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto) Storage::disk('public')->delete($produk->foto);
            $data['foto'] = $request->file('foto')->store("produk/{$produk->toko_id}", 'public');
        }

        $produk->update($data);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $this->authorizeProduk($produk);
        if ($produk->foto) Storage::disk('public')->delete($produk->foto);
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function authorizeProduk(Produk $produk): void
    {
        abort_if($produk->toko_id !== auth()->user()->toko?->id, 403);
    }
}