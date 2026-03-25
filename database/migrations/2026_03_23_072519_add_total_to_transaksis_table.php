<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->decimal('total', 12, 2)->default(0)->after('jumlah');
            $table->string('toko_id')->nullable()->after('total');
            $table->timestamp('expired_at')->nullable()->after('dibayar_at');
        });
    }

    public function down(): void {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['total', 'toko_id', 'expired_at']);
        });
    }
};