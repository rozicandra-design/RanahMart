<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nama'))
                $table->string('nama')->nullable(); // hapus ->after('name')
            if (!Schema::hasColumn('users', 'tanggal_lahir'))
                $table->date('tanggal_lahir')->nullable();
            if (!Schema::hasColumn('users', 'pengaturan_notifikasi'))
                $table->json('pengaturan_notifikasi')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'tanggal_lahir', 'pengaturan_notifikasi']);
        });
    }
};