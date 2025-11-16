<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SanPham;
use App\Models\DanhMuc;

class SanPhamController extends Controller
{
    /**
     * Lấy danh sách sản phẩm với filter và phân trang
     */
    public function index(Request $request)
    {
        $query = SanPham::with(['danhMuc', 'khuyenMaiSanPham' => function($q) {
            $q->where('ngay_bat_dau', '<=', now())
              ->where('ngay_ket_thuc', '>=', now());
        }]);

        // Filter theo danh mục
        if ($request->has('ma_danh_muc') && $request->ma_danh_muc) {
            $query->where('ma_danh_muc', $request->ma_danh_muc);
        }

        // Filter theo tên sản phẩm
        if ($request->has('search') && $request->search) {
            $query->where('ten_san_pham', 'LIKE', '%' . $request->search . '%');
        }

        // Filter theo giá
        if ($request->has('gia_tu') && $request->gia_tu) {
            $query->where('don_gia', '>=', $request->gia_tu);
        }
        if ($request->has('gia_den') && $request->gia_den) {
            $query->where('don_gia', '<=', $request->gia_den);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 12);
        $sanPham = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $sanPham,
            'message' => 'Lấy danh sách sản phẩm thành công'
        ]);
    }

    /**
     * Lấy sản phẩm nổi bật / bán chạy
     */
    public function featured()
    {
        $sanPhamNoiBat = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
            ->where('ton_kho', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sanPhamNoiBat,
            'message' => 'Lấy sản phẩm nổi bật thành công'
        ]);
    }

    /**
     * Tạo sản phẩm mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_san_pham' => 'required|string|max:150',
            'mo_ta' => 'nullable|string',
            'quy_cach' => 'nullable|string|max:100',
            'don_vi' => 'nullable|string|max:100',
            'don_gia' => 'required|numeric|min:0',
            'ton_kho' => 'required|integer|min:0',
            'url_hinh_anh' => 'nullable|string|max:255',
            'ma_danh_muc' => 'required|exists:danh_muc,ma_danh_muc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sanPham = SanPham::create($request->all());
            $sanPham->load('danhMuc');

            return response()->json([
                'success' => true,
                'data' => $sanPham,
                'message' => 'Tạo sản phẩm thành công'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết sản phẩm
     */
    public function show($id)
    {
        $sanPham = SanPham::with([
            'danhMuc',
            'khuyenMaiSanPham' => function($q) {
                $q->where('ngay_bat_dau', '<=', now())
                  ->where('ngay_ket_thuc', '>=', now());
            },
            'danhGiaSanPham' => function($q) {
                $q->where('da_duyet', true)
                  ->with(['khachHang', 'danhGiaLike'])
                  ->orderBy('created_at', 'desc');
            }
        ])->find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Tính điểm đánh giá trung bình
        $avgRating = $sanPham->danhGiaSanPham->avg('so_sao');
        $totalReviews = $sanPham->danhGiaSanPham->count();

        $sanPham->avg_rating = round($avgRating, 1);
        $sanPham->total_reviews = $totalReviews;

        return response()->json([
            'success' => true,
            'data' => $sanPham,
            'message' => 'Lấy thông tin sản phẩm thành công'
        ]);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(Request $request, $id)
    {
        $sanPham = SanPham::find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten_san_pham' => 'sometimes|required|string|max:150',
            'mo_ta' => 'nullable|string',
            'quy_cach' => 'nullable|string|max:100',
            'don_vi' => 'nullable|string|max:100',
            'don_gia' => 'sometimes|required|numeric|min:0',
            'ton_kho' => 'sometimes|required|integer|min:0',
            'url_hinh_anh' => 'nullable|string|max:255',
            'ma_danh_muc' => 'sometimes|required|exists:danh_muc,ma_danh_muc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sanPham->update($request->all());
            $sanPham->load('danhMuc');

            return response()->json([
                'success' => true,
                'data' => $sanPham,
                'message' => 'Cập nhật sản phẩm thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($id)
    {
        $sanPham = SanPham::find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        try {
            $sanPham->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy sản phẩm liên quan
     */
    public function related($id)
    {
        $sanPham = SanPham::find($id);

        if (!$sanPham) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        $sanPhamLienQuan = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
            ->where('ma_danh_muc', $sanPham->ma_danh_muc)
            ->where('ma_san_pham', '!=', $id)
            ->where('ton_kho', '>', 0)
            ->limit(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sanPhamLienQuan,
            'message' => 'Lấy sản phẩm liên quan thành công'
        ]);
    }
}
