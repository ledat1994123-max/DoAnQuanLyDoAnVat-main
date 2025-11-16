<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\KhachHang;
use App\Models\TaiKhoan;

class KhachHangController extends Controller
{
    /**
     * Lấy danh sách khách hàng (Admin)
     */
    public function index(Request $request)
    {
        $query = KhachHang::with('taiKhoan.vaiTro');

        // Filter theo tên khách hàng
        if ($request->has('search') && $request->search) {
            $query->where('ten_khach_hang', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('so_dien_thoai', 'LIKE', '%' . $request->search . '%');
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 15);
        $khachHang = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $khachHang,
            'message' => 'Lấy danh sách khách hàng thành công'
        ]);
    }

    /**
     * Tạo khách hàng mới (Admin)
     */
    public function store(Request $request)
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
            // Tạo tài khoản
            $taiKhoan = TaiKhoan::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau' => $request->mat_khau,
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

            $khachHang->load('taiKhoan.vaiTro');

            return response()->json([
                'success' => true,
                'data' => $khachHang,
                'message' => 'Tạo khách hàng thành công'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo khách hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết khách hàng
     */
    public function show($id)
    {
        $khachHang = KhachHang::with([
            'taiKhoan.vaiTro',
            'donHang' => function($query) {
                $query->orderBy('ngay_lap', 'desc')->limit(5);
            }
        ])->find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }

        // Thống kê đơn hàng của khách hàng
        $thongKe = [
            'tong_don_hang' => $khachHang->donHang()->count(),
            'don_hang_hoan_thanh' => $khachHang->donHang()->where('trang_thai', 'da_giao')->count(),
            'tong_chi_tieu' => $khachHang->donHang()->where('trang_thai', 'da_giao')->sum('tong_tien'),
            'don_hang_gan_nhat' => $khachHang->donHang()->orderBy('ngay_lap', 'desc')->first()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'khach_hang' => $khachHang,
                'thong_ke' => $thongKe
            ],
            'message' => 'Lấy thông tin khách hàng thành công'
        ]);
    }

    /**
     * Cập nhật thông tin khách hàng
     */
    public function update(Request $request, $id)
    {
        $khachHang = KhachHang::find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten_khach_hang' => 'sometimes|required|string|max:150',
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
            $khachHang->update($request->only([
                'ten_khach_hang',
                'so_dien_thoai', 
                'email',
                'dia_chi'
            ]));

            $khachHang->load('taiKhoan.vaiTro');

            return response()->json([
                'success' => true,
                'data' => $khachHang,
                'message' => 'Cập nhật thông tin khách hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa khách hàng
     */
    public function destroy($id)
    {
        $khachHang = KhachHang::with('donHang')->find($id);

        if (!$khachHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }

        // Kiểm tra xem khách hàng có đơn hàng không
        if ($khachHang->donHang()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa khách hàng vì đã có đơn hàng'
            ], 400);
        }

        try {
            $taiKhoan = $khachHang->taiKhoan;
            $khachHang->delete();
            
            if ($taiKhoan) {
                $taiKhoan->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Xóa khách hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa khách hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin profile khách hàng hiện tại
     */
    public function profile(Request $request)
    {
        // Giả sử đã có middleware authentication
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
            
            if (!$taiKhoan || $taiKhoan->id_vai_tro != 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền truy cập'
                ], 403);
            }

            $khachHang = $taiKhoan->khachHang;
            
            if (!$khachHang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin khách hàng'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $khachHang->load('taiKhoan.vaiTro'),
                'message' => 'Lấy thông tin profile thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ'
            ], 401);
        }
    }

    /**
     * Cập nhật profile khách hàng hiện tại
     */
    public function updateProfile(Request $request)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token không được cung cấp'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'ten_khach_hang' => 'sometimes|required|string|max:150',
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
            $decoded = base64_decode($token);
            $parts = explode(':', $decoded);
            $userId = $parts[0];

            $taiKhoan = TaiKhoan::find($userId);
            
            if (!$taiKhoan || $taiKhoan->id_vai_tro != 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền truy cập'
                ], 403);
            }

            $khachHang = $taiKhoan->khachHang;
            
            if (!$khachHang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin khách hàng'
                ], 404);
            }

            $khachHang->update($request->only([
                'ten_khach_hang',
                'so_dien_thoai',
                'email',
                'dia_chi'
            ]));

            return response()->json([
                'success' => true,
                'data' => $khachHang->load('taiKhoan.vaiTro'),
                'message' => 'Cập nhật profile thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
