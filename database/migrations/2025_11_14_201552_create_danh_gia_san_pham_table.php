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
        Schema::create('danh_gia_san_pham', function (Blueprint $table) {
            $table->id('ma_danh_gia');
            $table->foreignId('ma_san_pham')->constrained('san_pham', 'ma_san_pham');
            $table->foreignId('ma_khach_hang')->constrained('khach_hang', 'ma_khach_hang');
            $table->integer('so_sao')->between(1, 5);
            $table->text('noi_dung')->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->boolean('da_duyet')->default(false);
            $table->datetime('ngay_danh_gia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gia_san_pham');
    }
};
