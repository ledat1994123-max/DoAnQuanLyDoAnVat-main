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
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id('ma_don_hang');
            $table->foreignId('ma_khach_hang')->constrained('khach_hang', 'ma_khach_hang');
            $table->datetime('ngay_lap');
            $table->foreignId('id_km_don_hang_fk')->nullable()->constrained('khuyen_mai_don_hang', 'id_km_don_hang');
            $table->decimal('tong_tien', 10, 0);
            $table->string('dia_chi_giao_hang', 255);
            $table->string('phuong_thuc_thanh_toan', 150);
            $table->enum('trang_thai', ['cho_xac_nhan', 'da_xac_nhan', 'dang_chuan_bi', 'dang_giao', 'da_giao', 'da_huy'])->default('cho_xac_nhan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};
