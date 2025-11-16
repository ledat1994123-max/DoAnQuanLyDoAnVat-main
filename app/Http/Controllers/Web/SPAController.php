<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhMuc;
use App\Models\SanPham;

class SPAController extends Controller
{
    /**
     * Trang chủ - SPA Mode
     */
    public function home()
    {
        // Lấy danh mục sản phẩm
        $danhMuc = DanhMuc::withCount('sanPham')->get();
        
        // Lấy sản phẩm nổi bật (random 8 sản phẩm)
        $sanPhamNoiBat = SanPham::with('khuyenMaiSanPham')
            ->where('ton_kho', '>', 0)
            ->inRandomOrder()
            ->limit(8)
            ->get();
        
        // Lấy sản phẩm bán chạy (random 6 sản phẩm) 
        $sanPhamBanChay = SanPham::where('ton_kho', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('web.home', [
            'pageTitle' => 'Trang chủ - Food Ordering System',
            'apiBaseUrl' => url('/api'),
            'danhMuc' => $danhMuc,
            'sanPhamNoiBat' => $sanPhamNoiBat,
            'sanPhamBanChay' => $sanPhamBanChay
        ]);
    }

    /**
     * Trang sản phẩm - SPA Mode
     */
    public function products()
    {
        return view('web.products.index', [
            'pageTitle' => 'Sản phẩm - Food Ordering System',
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Chi tiết sản phẩm - SPA Mode
     */
    public function productDetail($id)
    {
        return view('web.products.index', [
            'pageTitle' => 'Chi tiết sản phẩm - Food Ordering System',
            'productId' => $id,
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Giỏ hàng - SPA Mode
     */
    public function cart()
    {
        return view('web.cart', [
            'pageTitle' => 'Giỏ hàng - Food Ordering System',
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Đăng nhập - SPA Mode
     */
    public function login()
    {
        return view('web.auth.login', [
            'pageTitle' => 'Đăng nhập - Food Ordering System',
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Đăng ký - SPA Mode
     */
    public function register()
    {
        return view('web.auth.register', [
            'pageTitle' => 'Đăng ký - Food Ordering System',
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Trang cá nhân - SPA Mode
     */
    public function profile()
    {
        return view('web.auth.profile', [
            'pageTitle' => 'Trang cá nhân - Food Ordering System',
            'apiBaseUrl' => url('/api')
        ]);
    }

    /**
     * Catch-all route cho SPA routing
     */
    public function index()
    {
        return view('web.home', [
            'pageTitle' => 'Food Ordering System',
            'apiBaseUrl' => url('/api'),
            'danhMuc' => collect([]), // Empty collection để tránh lỗi
            'sanPhamNoiBat' => collect([]),
            'sanPhamBanChay' => collect([])
        ]);
    }
}