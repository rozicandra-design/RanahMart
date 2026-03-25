<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('notifikasis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('judul');
        $table->text('pesan')->nullable();
        $table->string('tipe')->default('info'); // info, sukses, peringatan, error
        $table->string('url')->nullable();
        $table->boolean('sudah_dibaca')->default(false);
        $table->timestamps();
    });
}
};
