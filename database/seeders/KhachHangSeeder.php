<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KhachHang;

class KhachHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'id_tai_khoan' => 3, // khachhang1
                'ten_khach_hang' => 'Lê Thị Hoa',
                'so_dien_thoai' => '0987654321',
                'email' => 'hoa@gmail.com',
                'dia_chi' => '123 Nguyễn Trãi, Quận 1, TP.HCM'
            ],
            [
                'id_tai_khoan' => 4, // khachhang2
                'ten_khach_hang' => 'Phạm Văn Minh',
                'so_dien_thoai' => '0912345678',
                'email' => 'minh@gmail.com',
                'dia_chi' => '456 Lê Lợi, Quận 3, TP.HCM'
            ],
            [
                'id_tai_khoan' => 5, // khachhang3
                'ten_khach_hang' => 'Hoàng Thị Lan',
                'so_dien_thoai' => '0934567890',
                'email' => 'lan@yahoo.com',
                'dia_chi' => '789 Điện Biên Phủ, Quận Bình Thạnh, TP.HCM'
            ]
        ];

        foreach ($customers as $customer) {
            KhachHang::create($customer);
        }
    }
}
