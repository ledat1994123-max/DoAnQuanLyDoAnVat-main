<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SanPham;

class SanPhamSeeder extends Seeder
{
    public function run()
    {
        SanPham::insert([
            [
                'ten_san_pham' => 'Snack Khoai Tây Lay\'s BBQ',
                'mo_ta' => 'Giòn tan – vị BBQ',
                'don_gia' => 25000,
                'ton_kho' => 30,
                'url_hinh_anh' => 'lays.jpg',
                'ma_danh_muc' => 2
            ],
            [
                'ten_san_pham' => 'Trà Sữa Trân Châu',
                'mo_ta' => 'Ly size L',
                'don_gia' => 35000,
                'ton_kho' => 50,
                'url_hinh_anh' => 'trasua.jpg',
                'ma_danh_muc' => 3
            ]
        ]);
    }
}
