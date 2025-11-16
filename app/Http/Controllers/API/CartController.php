<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Lấy giỏ hàng hiện tại
     */
    public function index()
    {
        try {
            $cart = session('cart', []);
            $cartItems = [];
            $totalAmount = 0;
            $totalQuantity = 0;

            foreach ($cart as $productId => $item) {
                $product = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])->find($productId);
                
                if ($product) {
                    $price = $product->don_gia;
                    $discountPrice = $price;
                    
                    // Tính giá khuyến mãi nếu có
                    if ($product->khuyenMaiSanPham && $product->khuyenMaiSanPham->isNotEmpty()) {
                        $promotion = $product->khuyenMaiSanPham->first();
                        if ($promotion->loai_khuyen_mai == 'phan_tram') {
                            $discountPrice = $price * (1 - $promotion->gia_tri / 100);
                        } else {
                            $discountPrice = $price - $promotion->gia_tri;
                        }
                    }

                    $itemTotal = $discountPrice * $item['quantity'];
                    $totalAmount += $itemTotal;
                    $totalQuantity += $item['quantity'];

                    $cartItems[] = [
                        'product_id' => $productId,
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'discount_price' => $discountPrice,
                        'total' => $itemTotal,
                        'has_discount' => $price != $discountPrice
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Lấy giỏ hàng thành công',
                'data' => [
                    'items' => $cartItems,
                    'total_quantity' => $totalQuantity,
                    'total_amount' => $totalAmount,
                    'cart_count' => count($cartItems)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy giỏ hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer|exists:san_pham,ma_san_pham',
                'quantity' => 'required|integer|min:1'
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;

            $product = SanPham::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại'
                ], 404);
            }

            if ($product->ton_kho < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm không đủ trong kho'
                ], 400);
            }

            $cart = session('cart', []);

            if (isset($cart[$productId])) {
                $newQuantity = $cart[$productId]['quantity'] + $quantity;
                
                if ($newQuantity > $product->ton_kho) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng vượt quá số lượng trong kho'
                    ], 400);
                }
                
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'added_at' => now()
                ];
            }

            session(['cart' => $cart]);

            // Tính tổng số lượng trong giỏ
            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Thêm vào giỏ hàng thành công',
                'data' => [
                    'product_id' => $productId,
                    'quantity' => $cart[$productId]['quantity'],
                    'cart_count' => $cartCount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm vào giỏ hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateCart(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|min:0'
            ]);

            $productId = $request->product_id;
            $quantity = $request->quantity;
            $cart = session('cart', []);

            if (!isset($cart[$productId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có trong giỏ hàng'
                ], 404);
            }

            if ($quantity == 0) {
                unset($cart[$productId]);
            } else {
                $product = SanPham::find($productId);
                
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại'
                    ], 404);
                }

                if ($quantity > $product->ton_kho) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá số lượng trong kho'
                    ], 400);
                }

                $cart[$productId]['quantity'] = $quantity;
            }

            session(['cart' => $cart]);

            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công',
                'data' => [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'cart_count' => $cartCount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật giỏ hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart($productId)
    {
        try {
            $cart = session('cart', []);

            if (!isset($cart[$productId])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có trong giỏ hàng'
                ], 404);
            }

            unset($cart[$productId]);
            session(['cart' => $cart]);

            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
                'data' => [
                    'product_id' => $productId,
                    'cart_count' => $cartCount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clearCart()
    {
        try {
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Xóa toàn bộ giỏ hàng thành công',
                'data' => [
                    'cart_count' => 0
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa giỏ hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng
     */
    public function getCartCount()
    {
        try {
            $cart = session('cart', []);
            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'message' => 'Lấy số lượng giỏ hàng thành công',
                'data' => [
                    'cart_count' => $cartCount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy số lượng giỏ hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}