<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notif_pesanan_dikonfirmasi')->default(true);
            $table->boolean('notif_pesanan_dikirim')->default(true);
            $table->boolean('notif_pesanan_selesai')->default(true);
            $table->boolean('notif_promo')->default(true);
            $table->boolean('notif_flash_sale')->default(true);
            $table->boolean('privasi_tampilkan_nama')->default(true);
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notif_pesanan_dikonfirmasi',
                'notif_pesanan_dikirim',
                'notif_pesanan_selesai',
                'notif_promo',
                'notif_flash_sale',
                'privasi_tampilkan_nama',
            ]);
        });
    }
};