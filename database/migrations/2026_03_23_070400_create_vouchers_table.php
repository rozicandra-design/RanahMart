<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')->constrained()->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->string('tipe')->default('persen');
            $table->decimal('nilai', 10, 2);
            $table->decimal('min_belanja', 10, 2)->default(0);
            $table->integer('kuota')->default(1);
            $table->integer('terpakai')->default(0);
            $table->timestamp('berlaku_dari')->nullable();
            $table->timestamp('berlaku_sampai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('vouchers');
    }
};