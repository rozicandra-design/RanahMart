<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('iklans', function (Blueprint $table) {
            $table->unsignedBigInteger('harga')->default(0)->after('status');
            $table->string('paket')->nullable()->after('harga'); // basic, standard, premium
        });
    }

    public function down(): void {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropColumn(['harga', 'paket']);
        });
    }
};