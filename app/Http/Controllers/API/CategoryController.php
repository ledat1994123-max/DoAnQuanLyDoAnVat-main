<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMuc;

class CategoryController extends Controller
{
    /**
     * Lấy danh sách tất cả danh mục
     */
    public function index()
    {
        try {
            $categories = DanhMuc::where('trang_thai', 'hoat_dong')
                ->orderBy('ten_danh_muc', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách danh mục thành công',
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết danh mục
     */
    public function show($id)
    {
        try {
            $category = DanhMuc::with(['sanPham' => function($query) {
                $query->where('ton_kho', '>', 0)
                      ->with(['khuyenMaiSanPham' => function($q) {
                          $q->where('ngay_bat_dau', '<=', now())
                            ->where('ngay_ket_thuc', '>=', now());
                      }])
                      ->orderBy('created_at', 'desc')
                      ->limit(8);
            }])->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết danh mục thành công',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}