<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('produks', function (Blueprint $table) {
        $table->string('nama');
        $table->text('deskripsi')->nullable();
        $table->decimal('harga', 12, 2)->default(0);
        $table->string('kategori')->nullable();
        $table->string('foto')->nullable();
        $table->integer('stok')->default(0);
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
    });
}

public function down(): void
{
    Schema::table('produks', function (Blueprint $table) {
        $table->dropColumn(['nama', 'deskripsi', 'harga', 'kategori', 'foto', 'stok', 'user_id', 'status']);
    });
}
};
