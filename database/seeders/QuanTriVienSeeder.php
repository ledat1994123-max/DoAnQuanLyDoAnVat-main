<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuanTriVien;

class QuanTriVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'id_tai_khoan' => 1, // admin
                'ten_quan_tri_vien' => 'Admin Hệ Thống',
                'email' => 'admin@foodstore.com'
            ],
            [
                'id_tai_khoan' => 2, // nhanvien1
                'ten_quan_tri_vien' => 'Nhân Viên 1',
                'email' => 'nhanvien@foodstore.com'
            ]
        ];

        foreach ($admins as $admin) {
            QuanTriVien::create($admin);
        }
    }
}
