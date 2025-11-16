<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chi_tiet_don_hang', function (Blueprint $table) {
            $table->id('id_chi_tiet_dh');
            $table->foreignId('ma_don_hang')->constrained('don_hang', 'ma_don_hang');
            $table->foreignId('ma_san_pham')->constrained('san_pham', 'ma_san_pham');
            $table->integer('so_luong');
            $table->decimal('don_gia_luc_mua', 10, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_don_hang');
    }
};
