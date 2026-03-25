<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('toko_id')->constrained()->cascadeOnDelete();
            $table->string('kode_pesanan')->unique();
            $table->string('status_pesanan')->default('menunggu');
            $table->string('status_bayar')->default('belum_bayar');
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('ongkos_kirim', 10, 2)->default(0);
            $table->string('metode_pembayaran')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->string('kurir')->nullable();
            $table->string('no_resi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamp('dikirim_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesanans');
    }
};