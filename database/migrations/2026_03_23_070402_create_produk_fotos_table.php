<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produk_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->string('foto');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produk_fotos');
    }
};