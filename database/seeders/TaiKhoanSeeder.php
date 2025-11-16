<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaiKhoan;

class TaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'ten_dang_nhap' => 'admin',
                'mat_khau' => 'admin',
                'id_vai_tro' => 1, // Quản trị viên
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'nhanvien1',
                'mat_khau' => '123456',
                'id_vai_tro' => 3, // Nhân viên
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang1',
                'mat_khau' => '123456',
                'id_vai_tro' => 2, // Khách hàng
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang2',
                'mat_khau' => '123456',
                'id_vai_tro' => 2, // Khách hàng
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang3',
                'mat_khau' => '123456',
                'id_vai_tro' => 2, // Khách hàng
                'ngay_tao' => now()
            ]
        ];

        TaiKhoan::insert($accounts);
    }
}
