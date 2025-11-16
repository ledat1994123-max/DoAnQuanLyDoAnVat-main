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
        Schema::create('danh_gia_like', function (Blueprint $table) {
            $table->id('id_like');
            $table->foreignId('ma_danh_gia')->constrained('danh_gia_san_pham', 'ma_danh_gia');
            $table->foreignId('ma_khach_hang')->constrained('khach_hang', 'ma_khach_hang');
            $table->timestamps();
            
            // Đảm bảo một khách hàng chỉ có thể like một đánh giá một lần
            $table->unique(['ma_danh_gia', 'ma_khach_hang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_like');
    }
};
