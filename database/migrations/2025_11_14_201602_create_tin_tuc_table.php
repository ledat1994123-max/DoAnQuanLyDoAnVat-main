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
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->id('ma_tin');
            $table->string('tieu_de', 200);
            $table->string('tom_tat', 500)->nullable();
            $table->text('noi_dung');
            $table->string('url_hinh_anh', 255)->nullable();
            $table->datetime('ngay_dang');
            $table->foreignId('ma_quan_tri_vien')->constrained('quan_tri_vien', 'ma_quan_tri_vien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tin_tuc');
    }
};
