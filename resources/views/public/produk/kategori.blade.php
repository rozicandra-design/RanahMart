@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <h1 class="text-xl font-bold text-gray-800 mb-4">{{ $label }}</h1>

    @if($produks->isEmpty())
        <p class="text-gray-500">Belum ada produk di kategori ini.</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($produks as $produk)
            <div class="card">
                @if($produk->foto)
                <img src="{{ asset('storage/' . $produk->foto) }}" class="w-full h-40 object-cover rounded-lg mb-2">
                @else
                <div class="w-full h-40 bg-gray-100 rounded-lg mb-2 flex items-center justify-center text-gray-400 text-sm">No Image</div>
                @endif
                <p class="font-semibold text-sm text-gray-800">{{ $produk->nama }}</p>
                <p class="text-red-600 font-bold text-sm">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $produks->links() }}
        </div>
    @endif
</div>
@endsection