<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('produks', function (Blueprint $table) {
            if (!Schema::hasColumn('produks', 'deleted_at'))
                $table->softDeletes();
            if (!Schema::hasColumn('produks', 'slug'))
                $table->string('slug')->unique()->nullable();
            if (!Schema::hasColumn('produks', 'harga_coret'))
                $table->decimal('harga_coret', 12, 2)->nullable();
            if (!Schema::hasColumn('produks', 'berat'))
                $table->integer('berat')->nullable();
            if (!Schema::hasColumn('produks', 'total_terjual'))
                $table->integer('total_terjual')->default(0);
            if (!Schema::hasColumn('produks', 'toko_id'))
                $table->foreignId('toko_id')->nullable()->constrained('tokos')->nullOnDelete();
            if (!Schema::hasColumn('produks', 'catatan_review'))
                $table->text('catatan_review')->nullable();
        });
    }

    public function down(): void {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['slug', 'harga_coret', 'berat', 'total_terjual', 'catatan_review']);
        });
    }
};