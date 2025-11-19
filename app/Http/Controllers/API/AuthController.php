<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\TaiKhoan;
use App\Models\KhachHang;
use App\Models\QuanTriVien;

class AuthController extends Controller
{
    /**
     * Đăng ký tài khoản khách hàng
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_dang_nhap' => 'required|string|max:50|unique:tai_khoan',
            'mat_khau' => 'required|string|min:6',
            'ten_khach_hang' => 'required|string|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'dia_chi' => 'nullable|string|max:150'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tạo tài khoản - Lưu password trực tiếp không hash
            $taiKhoan = TaiKhoan::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau' => $request->mat_khau, // Lưu trực tiếp không hash
                'id_vai_tro' => 2, // Khách hàng
                'ngay_tao' => now()
            ]);

            // Tạo thông tin khách hàng
            $khachHang = KhachHang::create([
                'id_tai_khoan' => $taiKhoan->id_tai_khoan,
                'ten_khach_hang' => $request->ten_khach_hang,
                'so_dien_thoai' => $request->so_dien_thoai,
                'email' => $request->email,
                'dia_chi' => $request->dia_chi
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công',
                'data' => [
                    'tai_khoan' => $taiKhoan->load('vaiTro'),
                    'khach_hang' => $khachHang
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required',
            'mat_khau' => 'required'
        ]);

        // Tìm tài khoản theo username
        $user = TaiKhoan::where('ten_dang_nhap', $request->ten_dang_nhap)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sai tên đăng nhập'
            ], 401);
        }

        // So sánh mật khẩu PLAIN TEXT (không băm)
        if ($user->mat_khau !== $request->mat_khau) {
            return response()->json([
                'success' => false,
                'message' => 'Sai mật khẩu'
            ], 401);
        }

        // Tạo token  
        $token = $user->createToken("auth-token")->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Lấy thông tin tài khoản hiện tại
     */
    public function profile(Request $request)
    {
        // Giả sử token được gửi trong header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token không được cung cấp'
            ], 401);
        }

        try {
            $decoded = base64_decode($token);
            $parts = explode(':', $decoded);
            $userId = $parts[0];

            $taiKhoan = TaiKhoan::find($userId);

            if (!$taiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token không hợp lệ'
                ], 401);
            }

            $userInfo = null;
            if ($taiKhoan->id_vai_tro == 2) { // Khách hàng
                $userInfo = $taiKhoan->khachHang;
            } elseif ($taiKhoan->id_vai_tro == 1 || $taiKhoan->id_vai_tro == 3) { // Admin hoặc nhân viên
                $userInfo = $taiKhoan->quanTriVien;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tai_khoan' => $taiKhoan->load('vaiTro'),
                    'thong_tin' => $userInfo
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ'
            ], 401);
        }
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }
}
