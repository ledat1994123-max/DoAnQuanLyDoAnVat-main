<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $totalAmount = 0;
        
        foreach ($cart as $item) {
            $totalAmount += $item['don_gia'] * $item['so_luong'];
        }
        
        return view('web.cart', compact('cart', 'totalAmount'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'so_luong' => 'required|integer|min:1'
        ]);

        $sanPham = SanPham::findOrFail($request->ma_san_pham);
        
        // Kiểm tra tồn kho
        if ($sanPham->ton_kho < $request->so_luong) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không đủ tồn kho!'
            ], 400);
        }

        $cart = session('cart', []);
        
        if (isset($cart[$request->ma_san_pham])) {
            $cart[$request->ma_san_pham]['so_luong'] += $request->so_luong;
        } else {
            $cart[$request->ma_san_pham] = [
                'ten_san_pham' => $sanPham->ten_san_pham,
                'don_gia' => $sanPham->don_gia,
                'so_luong' => $request->so_luong,
                'url_hinh_anh' => $sanPham->url_hinh_anh,
                'don_vi' => $sanPham->don_vi
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cart_count' => count($cart)
        ]);
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        
        foreach ($request->quantities as $maSanPham => $soLuong) {
            if (isset($cart[$maSanPham])) {
                $cart[$maSanPham]['so_luong'] = max(1, intval($soLuong));
            }
        }
        
        session(['cart' => $cart]);
        
        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    public function removeFromCart($maSanPham)
    {
        $cart = session('cart', []);
        
        if (isset($cart[$maSanPham])) {
            unset($cart[$maSanPham]);
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cart_count' => count($cart)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tìm thấy trong giỏ hàng!'
        ], 404);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng!');
    }
}