<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\DanhGiaSanPham;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = SanPham::with(['danhMuc', 'khuyenMaiSanPham' => function($q) {
            $q->where('ngay_bat_dau', '<=', now())
              ->where('ngay_ket_thuc', '>=', now());
        }]);

        // Filter theo danh mục
        if ($request->has('danh_muc') && $request->danh_muc) {
            $query->where('ma_danh_muc', $request->danh_muc);
        }

        // Tìm kiếm theo tên
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

        // Chỉ hiển thị sản phẩm còn hàng
        $query->where('ton_kho', '>', 0);

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'gia_tang':
                $query->orderBy('don_gia', 'asc');
                break;
            case 'gia_giam':
                $query->orderBy('don_gia', 'desc');
                break;
            case 'ten':
                $query->orderBy('ten_san_pham', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $perPage = 12;
        $sanPham = $query->paginate($perPage);

        // Lấy danh mục để hiển thị filter
        $danhMuc = DanhMuc::orderBy('ten_danh_muc', 'asc')->get();

        return view('web.products.index', compact('sanPham', 'danhMuc'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
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
        ])->findOrFail($id);

        // Tính điểm đánh giá trung bình
        $avgRating = $sanPham->danhGiaSanPham->avg('so_sao');
        $totalReviews = $sanPham->danhGiaSanPham->count();
        $ratingDistribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $count = $sanPham->danhGiaSanPham->where('so_sao', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0
            ];
        }

        // Sản phẩm liên quan
        $sanPhamLienQuan = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
            ->where('ma_danh_muc', $sanPham->ma_danh_muc)
            ->where('ma_san_pham', '!=', $id)
            ->where('ton_kho', '>', 0)
            ->limit(4)
            ->get();

        return view('web.products.show', compact(
            'sanPham', 
            'avgRating', 
            'totalReviews', 
            'ratingDistribution', 
            'sanPhamLienQuan'
        ));
    }

    /**
     * Hiển thị sản phẩm theo danh mục
     */
    public function category($id)
    {
        $danhMuc = DanhMuc::findOrFail($id);
        
        $sanPham = SanPham::with(['khuyenMaiSanPham' => function($q) {
            $q->where('ngay_bat_dau', '<=', now())
              ->where('ngay_ket_thuc', '>=', now());
        }])
        ->where('ma_danh_muc', $id)
        ->where('ton_kho', '>', 0)
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('web.products.category', compact('danhMuc', 'sanPham'));
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        
        if (empty($keyword)) {
            return redirect()->route('products.index');
        }

        $sanPham = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
            ->where('ten_san_pham', 'LIKE', '%' . $keyword . '%')
            ->orWhere('mo_ta', 'LIKE', '%' . $keyword . '%')
            ->where('ton_kho', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('web.products.search', compact('sanPham', 'keyword'));
    }

    /**
     * API để lấy gợi ý tìm kiếm
     */
    public function suggestions(Request $request)
    {
        $keyword = $request->get('q', '');
        
        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $suggestions = SanPham::where('ten_san_pham', 'LIKE', '%' . $keyword . '%')
            ->where('ton_kho', '>', 0)
            ->limit(5)
            ->pluck('ten_san_pham')
            ->toArray();

        return response()->json($suggestions);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (AJAX)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'so_luong' => 'required|integer|min:1'
        ]);

        $sanPham = SanPham::find($request->ma_san_pham);
        
        if (!$sanPham || $sanPham->ton_kho < $request->so_luong) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có đủ số lượng trong kho'
            ], 400);
        }

        // Lưu vào session giỏ hàng
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->ma_san_pham])) {
            $cart[$request->ma_san_pham]['so_luong'] += $request->so_luong;
        } else {
            $cart[$request->ma_san_pham] = [
                'ma_san_pham' => $sanPham->ma_san_pham,
                'ten_san_pham' => $sanPham->ten_san_pham,
                'don_gia' => $sanPham->don_gia,
                'so_luong' => $request->so_luong,
                'url_hinh_anh' => $sanPham->url_hinh_anh
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['don_gia'] * $item['so_luong'];
        }

        return view('web.cart', compact('cart', 'totalAmount'));
    }

    /**
     * Cập nhật giỏ hàng
     */
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if ($request->has('quantities')) {
            foreach ($request->quantities as $maSanPham => $soLuong) {
                if ($soLuong <= 0) {
                    unset($cart[$maSanPham]);
                } else {
                    if (isset($cart[$maSanPham])) {
                        $cart[$maSanPham]['so_luong'] = $soLuong;
                    }
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart($maSanPham)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$maSanPham])) {
            unset($cart[$maSanPham]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => count($cart)
        ]);
    }
}
