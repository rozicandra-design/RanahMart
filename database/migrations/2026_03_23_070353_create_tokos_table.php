<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_toko');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('terverifikasi_dinas')->default(false);
            $table->date('tanggal_sertifikat')->nullable();
            $table->date('kadaluarsa_sertifikat')->nullable();
            $table->string('nib')->nullable();
            $table->string('no_sku')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_usaha')->nullable();
            $table->string('foto_produk_sample')->nullable();
            $table->text('catatan_dinas')->nullable();
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->string('nama_kepala_dinas')->nullable();
            $table->string('jabatan_kepala_dinas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tokos');
    }
};