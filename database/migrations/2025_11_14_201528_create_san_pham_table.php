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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id('ma_san_pham');
            $table->string('ten_san_pham', 150);
            $table->text('mo_ta')->nullable();
            $table->string('quy_cach', 100)->nullable();
            $table->string('don_vi', 100)->nullable();
            $table->decimal('don_gia', 10, 0);
            $table->integer('ton_kho')->default(0);
            $table->string('url_hinh_anh', 255)->nullable();
            $table->foreignId('ma_danh_muc')->constrained('danh_muc', 'ma_danh_muc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};
