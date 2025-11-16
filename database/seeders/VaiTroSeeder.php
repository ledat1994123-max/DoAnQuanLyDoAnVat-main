<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VaiTro;

class VaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'ten_vai_tro' => 'Quản trị viên',
                'mo_ta' => 'Quản lý toàn bộ hệ thống, có quyền cao nhất'
            ],
            [
                'ten_vai_tro' => 'Khách hàng',
                'mo_ta' => 'Người dùng thường, có thể đặt hàng và mua sắm'
            ],
            [
                'ten_vai_tro' => 'Nhân viên',
                'mo_ta' => 'Nhân viên hỗ trợ khách hàng và xử lý đơn hàng'
            ]
        ];

        foreach ($roles as $role) {
            VaiTro::create($role);
        }
    }
}
