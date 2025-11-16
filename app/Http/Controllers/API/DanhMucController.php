<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DanhMuc;

class DanhMucController extends Controller
{
    /**
     * Lấy danh sách tất cả danh mục
     */
    public function index(Request $request)
    {
        $query = DanhMuc::withCount('sanPham');

        // Filter theo tên danh mục
        if ($request->has('search') && $request->search) {
            $query->where('ten_danh_muc', 'LIKE', '%' . $request->search . '%');
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $danhMuc = $query->get();

        return response()->json([
            'success' => true,
            'data' => $danhMuc,
            'message' => 'Lấy danh sách danh mục thành công'
        ]);
    }

    /**
     * Tạo danh mục mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_danh_muc' => 'required|string|max:100|unique:danh_muc',
            'mota' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $danhMuc = DanhMuc::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $danhMuc,
                'message' => 'Tạo danh mục thành công'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin chi tiết danh mục
     */
    public function show($id)
    {
        $danhMuc = DanhMuc::with(['sanPham' => function($query) {
            $query->where('ton_kho', '>', 0)
                  ->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $danhMuc,
            'message' => 'Lấy thông tin danh mục thành công'
        ]);
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ten_danh_muc' => 'sometimes|required|string|max:100|unique:danh_muc,ten_danh_muc,' . $id . ',ma_danh_muc',
            'mota' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $danhMuc->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $danhMuc,
                'message' => 'Cập nhật danh mục thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        // Kiểm tra xem danh mục có sản phẩm không
        if ($danhMuc->sanPham()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa danh mục vì còn có sản phẩm thuộc danh mục này'
            ], 400);
        }

        try {
            $danhMuc->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa danh mục thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách sản phẩm theo danh mục với phân trang
     */
    public function products($id, Request $request)
    {
        $danhMuc = DanhMuc::find($id);

        if (!$danhMuc) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục'
            ], 404);
        }

        $query = $danhMuc->sanPham()->with(['khuyenMaiSanPham' => function($q) {
            $q->where('ngay_bat_dau', '<=', now())
              ->where('ngay_ket_thuc', '>=', now());
        }]);

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

        // Chỉ lấy sản phẩm còn hàng
        if ($request->get('con_hang', false)) {
            $query->where('ton_kho', '>', 0);
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
            'data' => [
                'danh_muc' => $danhMuc,
                'san_pham' => $sanPham
            ],
            'message' => 'Lấy danh sách sản phẩm theo danh mục thành công'
        ]);
    }
}
