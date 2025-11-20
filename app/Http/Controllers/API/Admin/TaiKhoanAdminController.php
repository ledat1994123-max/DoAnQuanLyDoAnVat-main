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
                'mat_khau'      => $request->mat_khau,
                'id_vai_tro'    => $request->id_vai_tro,
                'ngay_tao'      => now()
            ]);

            // 2. Nếu là KHÁCH HÀNG → Lưu thêm bảng khách_hang
            if ($request->id_vai_tro == 2) {
                KhachHang::create([
                    'id_tai_khoan'   => $user->id_tai_khoan,
                    'ten_khach_hang' => $request->ten_khach_hang,
                    'so_dien_thoai'  => $request->so_dien_thoai,
                    'email'          => $request->email,
                    'dia_chi'        => $request->dia_chi
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm tài khoản thành công',
                'data' => $user
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

    /**
     * Cập nhật tài khoản (Admin)
     */
    public function update(Request $request, $id)
    {
        $user = TaiKhoan::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy tài khoản'], 404);
        }

        DB::beginTransaction();

        try {
            $data = [];
            if ($request->has('ten_dang_nhap')) $data['ten_dang_nhap'] = $request->ten_dang_nhap;
            if ($request->has('id_vai_tro')) $data['id_vai_tro'] = $request->id_vai_tro;
            if ($request->has('mat_khau') && $request->mat_khau) $data['mat_khau'] = $request->mat_khau;

            if (!empty($data)) {
                $user->update($data);
            }

            // Nếu là khách hàng thì cập nhật bảng khach_hang
            if ($user->id_vai_tro == 2) {
                $kh = $user->khachHang;
                if ($kh) {
                    $kh->update($request->only(['ten_khach_hang', 'so_dien_thoai', 'email', 'dia_chi']));
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công', 'data' => $user]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi khi cập nhật', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Xóa tài khoản (Admin)
     */
    public function destroy($id)
    {
        $user = TaiKhoan::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy tài khoản'], 404);
        }

        try {
            // Xóa các thông tin liên quan
            if ($user->khachHang) $user->khachHang->delete();
            if ($user->quanTriVien) $user->quanTriVien->delete();

            $user->delete();

            return response()->json(['success' => true, 'message' => 'Xóa tài khoản thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi khi xóa tài khoản', 'error' => $e->getMessage()], 500);
        }
    }
}
