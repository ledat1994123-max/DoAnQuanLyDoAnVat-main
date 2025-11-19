<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\TaiKhoan;
use App\Models\KhachHang;

class TaiKhoanAdminController extends Controller
{
    public function index()
    {
        $users = TaiKhoan::with('vaiTro')->get();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. Tạo tài khoản
            $user = TaiKhoan::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau'      => Hash::make($request->mat_khau),
                'id_vai_tro'    => $request->id_vai_tro
            ]);

            // 2. Nếu là KHÁCH HÀNG → Lưu thêm bảng khách_hang
            if ($request->id_vai_tro == 2) {
                KhachHang::create([
                    'id_tai_khoan'   => $user->ma_tai_khoan,
                    'ten_khach_hang' => $request->ten_khach_hang,
                    'so_dien_thoai'  => $request->so_dien_thoai,
                    'email'          => $request->email,
                    'dia_chi'        => $request->dia_chi
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm tài khoản thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm tài khoản',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
