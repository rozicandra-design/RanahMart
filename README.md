# RanahMart
# 🛒 RanahMart

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/TailwindCSS-3-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge"/>
</p>

<p align="center">
  Platform e-commerce UMKM lokal berbasis Laravel — menghubungkan pelaku usaha, pembeli, dan dinas pemerintah dalam satu ekosistem terintegrasi.
</p>

---

## 📌 Tentang RanahMart

**RanahMart** adalah marketplace berbasis web yang dirancang khusus untuk memberdayakan UMKM lokal di Kota Padang. Platform ini mengintegrasikan empat peran utama — Admin, Penjual (UMKM), Pembeli, dan Dinas Koperasi — dalam satu sistem yang saling terhubung, mulai dari transaksi jual beli hingga verifikasi & sertifikasi resmi UMKM.

---

## ✨ Fitur Lengkap per Peran

### 🧑‍💼 Admin
- Dashboard statistik platform
- Manajemen pengguna (aktif / nonaktif / reset password)
- Review & moderasi produk (setujui / tolak / turunkan / peringatkan)
- Review & moderasi UMKM (setujui / tolak / minta dokumen / teruskan ke dinas / nonaktifkan)
- Manajemen iklan (setujui / tolak / revisi / hentikan)
- Monitoring transaksi & ekspor laporan
- Manajemen retur (setujui / tolak)
- Laporan & ekspor PDF
- Pengaturan platform & notifikasi

### 🏪 Penjual (UMKM)
- Registrasi toko multi-step (data diri → profil toko → upload dokumen)
- Dashboard penjualan
- Manajemen produk CRUD + toggle status aktif/nonaktif
- Manajemen pesanan (konfirmasi / tolak / tandai dikirim)
- Keuangan: saldo, pencairan dana, riwayat
- Balas ulasan pelanggan
- Buat & kelola iklan toko (paket Basic / Standard / Premium)
- Promo & diskon (toggle aktif/nonaktif)
- Upload & kelola dokumen toko
- Lihat sertifikat toko dari Dinas
- Laporan penjualan & cetak PDF
- Notifikasi & pengaturan akun

### 🛍️ Pembeli
- Browse produk, kategori, flash sale
- Keranjang belanja (tambah / update qty / hapus / kosongkan)
- Checkout & konfirmasi penerimaan pesanan
- Batalkan pesanan
- Wishlist produk
- Tulis ulasan & rating
- Pengajuan retur barang
- Manajemen alamat pengiriman (multi-alamat, set alamat utama)
- Voucher & validasi kode voucher
- Sistem poin loyalitas & penukaran poin
- Edit profil & ganti password
- Notifikasi & pengaturan akun

### 🏛️ Dinas Koperasi
- Dashboard monitoring UMKM
- Verifikasi UMKM (setujui / tolak / minta dokumen / jadwal kunjungan lapangan)
- Terbitkan & cetak sertifikat UMKM (PDF)
- Monitoring toko & produk aktif
- Statistik wilayah & ekspor PDF
- Program pembinaan UMKM (daftarkan UMKM ke program)
- Pengumuman untuk pelaku UMKM
- Laporan & ekspor data
- Notifikasi & pengaturan akun

---

## 🗂️ Peran & Akses

| Role | Prefix URL | Deskripsi |
|---|---|---|
| `admin` | `/admin` | Superadmin platform |
| `penjual` | `/penjual` | Pelaku UMKM / pemilik toko |
| `pembeli` | `/pembeli` | Konsumen / pelanggan |
| `dinas` | `/dinas` | Dinas Koperasi Kota Padang |

---

## 🏷️ Kategori UMKM

| Key | Label |
|---|---|
| `makanan_minuman` | Makanan & Minuman |
| `kerajinan` | Kerajinan Tangan |
| `fashion` | Fashion & Pakaian |
| `herbal_kesehatan` | Herbal & Kesehatan |
| `seni_budaya` | Seni & Budaya |
| `kecantikan` | Kecantikan |
| `lainnya` | Lainnya |

---

## 📢 Paket Iklan

| Paket | Harga | Durasi |
|---|---|---|
| Basic | Rp 50.000 | 7 hari |
| Standard | Rp 150.000 | 14 hari |
| Premium | Rp 300.000 | 30 hari |

---

## 🗃️ Entity Relationship Diagram (ERD)

> 📌 *Tambahkan gambar ERD kamu di sini*

Relasi utama antar tabel:

```
users           → toko, alamat, pesanan, keranjang, wishlist, poin, notifikasi, voucher_user
toko            → produk, iklan, kunjungan_lapangan, sertifikat
produk          → produk_foto, kategori, item_pesanan, keranjang, wishlist, ulasan
pesanan         → item_pesanan, transaksi, retur
promo/voucher   → voucher_user
poin            → users
notifikasi      → users
```

> 💡 Untuk ERD lengkap, lihat folder `database/migrations/` atau gunakan [dbdiagram.io](https://dbdiagram.io).

---

## 🖼️ Screenshot

> 📌 *Tambahkan screenshot aplikasi kamu di sini*

| Halaman | Preview |
|---|---|
| Home / Beranda | *(tambahkan screenshot)* |
| Dashboard Admin | *(tambahkan screenshot)* |
| Dashboard Penjual | *(tambahkan screenshot)* |
| Dashboard Pembeli | *(tambahkan screenshot)* |
| Dashboard Dinas | *(tambahkan screenshot)* |
| Halaman Produk | *(tambahkan screenshot)* |
| Keranjang & Checkout | *(tambahkan screenshot)* |
| Sertifikat UMKM | *(tambahkan screenshot)* |

---

## 🛠️ Tech Stack

| Teknologi | Versi | Keterangan |
|---|---|---|
| **Laravel** | 13 | Backend framework |
| **PHP** | ^8.3 | Bahasa pemrograman |
| **MySQL** | 8 | Database |
| **Tailwind CSS** | 3 | Styling / UI |
| **Vite** | latest | Asset bundler |
| **barryvdh/laravel-dompdf** | * | Generate laporan PDF |
| **spatie/browsershot** | ^5.2 | Render PDF via browser |

---

## ⚙️ Instalasi

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL 8

### Cara Cepat (Shortcut)

```bash
git clone https://github.com/rozicandra-design/RanahMart.git
cd RanahMart
composer run setup
```

> Perintah `composer run setup` otomatis menjalankan: install dependencies → copy `.env` → generate key → migrate → install npm → build asset.

Lalu jalankan server:
```bash
composer run dev
```

> Menjalankan sekaligus: Laravel server, queue listener, log watcher (Pail), dan Vite dev server.

---

### Instalasi Manual

```bash
# 1. Clone
git clone https://github.com/rozicandra-design/RanahMart.git
cd RanahMart

# 2. Install dependency
composer install
npm install

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk koneksi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ranahmart
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Migrasi & seeder
php artisan migrate --seed

# 5. Storage link
php artisan storage:link

# 6. Build frontend
npm run build

# 7. Jalankan server
php artisan serve
```

Akses di: **http://localhost:8000**

---

## 👤 Akun Default (Seeder)

| Nama | Email | Role | Password |
|---|---|---|---|
| Super Admin | admin@ranahmart.com | admin | password |
| Dinas Koperasi Padang | dinas@ranahmart.com | dinas | password |
| Rendang Uni Siti | unisiti@ranahmart.com | penjual | password |
| Kerajinan Pak Budi | pakbudi@ranahmart.com | penjual | password |
| Batik Minang Store | batikminang@ranahmart.com | penjual | password |
| Budi Santoso | budi@gmail.com | pembeli | password |
| Sari Dewi | sari@gmail.com | pembeli | password |
| Ahmad Fauzi | ahmad@gmail.com | pembeli | password |

---

## ⏰ Scheduled Commands

Tambahkan cron job di server:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

| Command | Fungsi |
|---|---|
| `ExpireVoucher` | Menonaktifkan voucher yang sudah kedaluwarsa |
| `PoinExpireCheck` | Mengecek & menghapus poin yang kedaluwarsa |
| `UpdateIklanStatus` | Memperbarui status iklan secara otomatis |

---

## 📁 Struktur Direktori

```
RanahMart/
├── app/
│   ├── Console/Commands/          # Scheduled commands
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Controllers admin
│   │   │   ├── Pembeli/           # Controllers pembeli
│   │   │   ├── Penjual/           # Controllers penjual
│   │   │   ├── Dinas/             # Controllers dinas
│   │   │   ├── Public/            # Controllers halaman publik
│   │   │   └── Auth/              # Login & Register
│   │   ├── Middleware/            # RoleMiddleware, CheckTokoAktif
│   │   └── Requests/              # Form Request validation
│   ├── Models/                    # Eloquent models
│   ├── Services/                  # Business logic (Poin, Notifikasi, Pembayaran, Iklan)
│   ├── Policies/                  # Authorization policies
│   └── Helpers/                   # Helper functions global
├── config/
│   └── ranahmart.php              # Konfigurasi kategori UMKM, kecamatan, paket iklan
├── database/
│   ├── migrations/                # Semua migrasi tabel
│   └── seeders/                   # DatabaseSeeder & UserSeeder
├── resources/views/
│   ├── admin/                     # Views admin
│   ├── pembeli/                   # Views pembeli
│   ├── penjual/                   # Views penjual
│   ├── dinas/                     # Views dinas
│   ├── public/                    # Views halaman publik
│   ├── auth/                      # Views login & register (multi-step UMKM)
│   ├── components/                # Reusable Blade components
│   ├── layouts/                   # Layout utama (app, auth, dashboard)
│   └── errors/                    # Halaman error (403, 404, 500)
└── routes/
    └── web.php                    # Semua routing aplikasi
```

---

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch fitur baru
   ```bash
   git checkout -b feat/nama-fitur
   ```
3. Commit perubahan
   ```bash
   git commit -m "feat: deskripsi singkat"
   ```
4. Push & buat Pull Request ke branch `main`

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

## 👨‍💻 Developer

Dibuat dengan ❤️ oleh [@rozicandra-design](https://github.com/rozicandra-design)

---

<p align="center">⭐ Jangan lupa kasih bintang kalau project ini bermanfaat!</p>