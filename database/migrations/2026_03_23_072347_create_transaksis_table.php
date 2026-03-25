<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode_transaksi')->unique();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaksis');
    }
};