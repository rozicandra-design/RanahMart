<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->string('email')->unique();
            $table->string('no_hp', 20)->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'penjual', 'pembeli', 'dinas'])->default('pembeli');
            $table->enum('status', ['aktif', 'nonaktif', 'diblokir'])->default('aktif');
            $table->string('foto_profil')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
            $table->string('kota')->default('Padang');
            $table->string('kecamatan')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};