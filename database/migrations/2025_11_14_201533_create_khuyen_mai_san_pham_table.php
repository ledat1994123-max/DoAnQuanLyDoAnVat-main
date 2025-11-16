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
        Schema::create('khuyen_mai_san_pham', function (Blueprint $table) {
            $table->id('ma_khuyen_mai');
            $table->foreignId('ma_san_pham')->constrained('san_pham', 'ma_san_pham');
            $table->decimal('phan_tram_giam_gia', 5, 2);
            $table->datetime('ngay_bat_dau');
            $table->datetime('ngay_ket_thuc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_san_pham');
    }
};
