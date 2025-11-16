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
        Schema::create('khuyen_mai_don_hang', function (Blueprint $table) {
            $table->id('id_km_don_hang');
            $table->string('code', 50);
            $table->decimal('so_tien_giam', 10, 0);
            $table->datetime('han_su_dung');
            $table->integer('trang_thai')->default(1); // 1: active, 0: inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_don_hang');
    }
};
