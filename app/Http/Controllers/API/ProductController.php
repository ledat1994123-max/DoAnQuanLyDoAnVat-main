<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\DanhGiaSanPham;

class ProductController extends Controller
{
    /**
     * Lấy danh sách sản phẩm với phân trang và filter
     */
    public function index(Request $request)
    {
        try {
            $query = SanPham::with(['danhMuc', 'khuyenMaiSanPham' => function($q) {
                $q->where('ngay_bat_dau', '<=', now())
                  ->where('ngay_ket_thuc', '>=', now());
            }]);

            // Filter theo danh mục
            if ($request->has('category_id') && $request->category_id) {
                $query->where('ma_danh_muc', $request->category_id);
            }

            // Tìm kiếm theo tên
            if ($request->has('search') && $request->search) {
                $query->where('ten_san_pham', 'LIKE', '%' . $request->search . '%');
            }

            // Filter theo giá
            if ($request->has('min_price') && $request->min_price) {
                $query->where('don_gia', '>=', $request->min_price);
            }
            if ($request->has('max_price') && $request->max_price) {
                $query->where('don_gia', '<=', $request->max_price);
            }

            // Chỉ hiển thị sản phẩm còn hàng
            $query->where('ton_kho', '>', 0);

            // Sắp xếp
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            switch ($sortBy) {
                case 'price_asc':
                    $query->orderBy('don_gia', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('don_gia', 'desc');
                    break;
                case 'name':
                    $query->orderBy('ten_san_pham', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $perPage = $request->get('per_page', 12);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách sản phẩm thành công',
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm
     */
    public function show($id)
    {
        try {
            $product = SanPham::with([
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

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            // Tính điểm đánh giá trung bình
            $avgRating = $product->danhGiaSanPham->avg('so_sao');
            $totalReviews = $product->danhGiaSanPham->count();
            $ratingDistribution = [];
            
            for ($i = 1; $i <= 5; $i++) {
                $count = $product->danhGiaSanPham->where('so_sao', $i)->count();
                $ratingDistribution[$i] = [
                    'count' => $count,
                    'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0
                ];
            }

            // Sản phẩm liên quan
            $relatedProducts = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
                ->where('ma_danh_muc', $product->ma_danh_muc)
                ->where('ma_san_pham', '!=', $id)
                ->where('ton_kho', '>', 0)
                ->limit(4)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết sản phẩm thành công',
                'data' => [
                    'product' => $product,
                    'avg_rating' => round($avgRating, 1),
                    'total_reviews' => $totalReviews,
                    'rating_distribution' => $ratingDistribution,
                    'related_products' => $relatedProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->get('q', '');
            
            if (empty($keyword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Từ khóa tìm kiếm không được để trống'
                ], 400);
            }

            $products = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
                ->where(function($query) use ($keyword) {
                    $query->where('ten_san_pham', 'LIKE', '%' . $keyword . '%')
                          ->orWhere('mo_ta', 'LIKE', '%' . $keyword . '%');
                })
                ->where('ton_kho', '>', 0)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm sản phẩm thành công',
                'data' => $products->items(),
                'keyword' => $keyword,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tìm kiếm sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gợi ý tìm kiếm
     */
    public function suggestions(Request $request)
    {
        try {
            $keyword = $request->get('q', '');
            
            if (strlen($keyword) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $suggestions = SanPham::where('ten_san_pham', 'LIKE', '%' . $keyword . '%')
                ->where('ton_kho', '>', 0)
                ->limit(5)
                ->pluck('ten_san_pham')
                ->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Lấy gợi ý thành công',
                'data' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy gợi ý',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy sản phẩm theo danh mục
     */
    public function getByCategory($categoryId, Request $request)
    {
        try {
            $category = DanhMuc::find($categoryId);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy danh mục'
                ], 404);
            }

            $products = SanPham::with(['khuyenMaiSanPham' => function($q) {
                $q->where('ngay_bat_dau', '<=', now())
                  ->where('ngay_ket_thuc', '>=', now());
            }])
            ->where('ma_danh_muc', $categoryId)
            ->where('ton_kho', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

            return response()->json([
                'success' => true,
                'message' => 'Lấy sản phẩm theo danh mục thành công',
                'data' => [
                    'category' => $category,
                    'products' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy sản phẩm theo danh mục',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy sản phẩm nổi bật cho trang chủ
     */
    public function featured()
    {
        try {
            $featuredProducts = SanPham::with(['danhMuc', 'khuyenMaiSanPham' => function($q) {
                $q->where('ngay_bat_dau', '<=', now())
                  ->where('ngay_ket_thuc', '>=', now());
            }])
            ->where('ton_kho', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy sản phẩm nổi bật thành công',
                'data' => $featuredProducts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy sản phẩm nổi bật',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy sản phẩm bán chạy
     */
    public function bestsellers()
    {
        try {
            $bestsellers = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
                ->where('ton_kho', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Lấy sản phẩm bán chạy thành công',
                'data' => $bestsellers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy sản phẩm bán chạy',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}