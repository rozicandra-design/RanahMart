<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('iklans', function (Blueprint $table) {
            if (!Schema::hasColumn('iklans', 'toko_id'))
                $table->foreignId('toko_id')->nullable()->constrained('tokos')->nullOnDelete();
            if (!Schema::hasColumn('iklans', 'user_id'))
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            if (!Schema::hasColumn('iklans', 'status'))
                $table->string('status')->default('nonaktif');
            if (!Schema::hasColumn('iklans', 'tanggal_mulai'))
                $table->timestamp('tanggal_mulai')->nullable();
            if (!Schema::hasColumn('iklans', 'tanggal_selesai'))
                $table->timestamp('tanggal_selesai')->nullable();
            if (!Schema::hasColumn('iklans', 'gambar'))
                $table->string('gambar')->nullable();
            if (!Schema::hasColumn('iklans', 'judul'))
                $table->string('judul')->nullable();
            if (!Schema::hasColumn('iklans', 'deskripsi'))
                $table->text('deskripsi')->nullable();
            if (!Schema::hasColumn('iklans', 'deleted_at'))
                $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropColumn(['toko_id', 'user_id', 'status', 'tanggal_mulai', 'tanggal_selesai', 'gambar', 'judul', 'deskripsi']);
            $table->dropSoftDeletes();
        });
    }
};