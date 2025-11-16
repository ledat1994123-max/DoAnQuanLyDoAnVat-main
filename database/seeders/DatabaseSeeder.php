<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VaiTroSeeder::class,
            TaiKhoanSeeder::class,
            QuanTriVienSeeder::class,
            KhachHangSeeder::class,
            DanhMucSeeder::class,
            SanPhamSeeder::class,
        ]);
    }
}
