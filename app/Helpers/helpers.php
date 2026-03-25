<?php

if (! function_exists('nama_kategori')) {
    function nama_kategori(string $kategori): string
    {
        return match($kategori) {
            'makanan_minuman'  => 'Makanan & Minuman',
            'fashion'          => 'Fashion & Pakaian',
            'kerajinan'        => 'Kerajinan Tangan',
            'herbal_kesehatan' => 'Herbal & Kesehatan',
            'seni_budaya'      => 'Seni & Budaya',
            'kecantikan'       => 'Kecantikan',
            default            => 'Lainnya',
        };
    }
}