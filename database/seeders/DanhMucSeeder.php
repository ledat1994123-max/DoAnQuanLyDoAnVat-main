<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DanhMuc;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'ten_danh_muc' => 'Bánh kẹo',
                'mota' => 'Các loại bánh ngọt, kẹo, chocolate và đồ ngọt khác'
            ],
            [
                'ten_danh_muc' => 'Snack mặn',
                'mota' => 'Khoai tây chiên, bánh tráng nướng, nem chua rán và các món ăn vặt mặn'
            ],
            [
                'ten_danh_muc' => 'Nước uống',
                'mota' => 'Nước ngọt, trà sữa, nước ép và các loại đồ uống giải khát'
            ],
            [
                'ten_danh_muc' => 'Mì tôm & Mì ăn liền',
                'mota' => 'Các loại mì tôm, mì ăn liền và thực phẩm chế biến nhanh'
            ],
            [
                'ten_danh_muc' => 'Trái cây sấy khô',
                'mota' => 'Xoài sấy, chuối sấy, mít sấy và các loại trái cây sấy khô'
            ],
            [
                'ten_danh_muc' => 'Kem & Đông lạnh',
                'mota' => 'Kem que, kem hộp, bánh flan và các sản phẩm đông lạnh'
            ]
        ];

        foreach ($categories as $category) {
            DanhMuc::create($category);
        }
    }
}
