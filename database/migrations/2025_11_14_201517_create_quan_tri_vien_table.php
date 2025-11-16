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
        Schema::create('quan_tri_vien', function (Blueprint $table) {
            $table->id('ma_quan_tri_vien');
            $table->foreignId('id_tai_khoan')->constrained('tai_khoan', 'id_tai_khoan');
            $table->string('ten_quan_tri_vien', 100);
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quan_tri_vien');
    }
};
