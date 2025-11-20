<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;

class SanPhamAdminController extends Controller
{
    /**
     * Lấy danh sách sản phẩm (Admin)
     */
    public function index(Request $request)
    {
        try {
            $query = SanPham::with('danhMuc');

            // Filter theo danh mục
            if ($request->has('category_id') && $request->category_id) {
                $query->where('ma_danh_muc', $request->category_id);
            }

            // Tìm kiếm theo tên
            if ($request->has('search') && $request->search) {
                $query->where('ten_san_pham', 'LIKE', '%' . $request->search . '%');
            }

            $products = $query->get();

            return response()->json([
                'success' => true,
                'data' => $products
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
     * Thêm sản phẩm (Admin)
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'ten_san_pham' => 'required|string|max:255',
                'don_gia' => 'required|numeric|min:1',
                'ton_kho' => 'required|integer|min:0',
                'ma_danh_muc' => 'required|integer',
                'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            $imageUrl = null;
            // Xử lý upload file
            if ($request->hasFile('hinh_anh')) {
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);
                $imageUrl = '/images/products/' . $filename;
            }

            $data = [
                'ten_san_pham'  => $request->ten_san_pham,
                'mo_ta'         => $request->mo_ta ?? null,
                'quy_cach'      => $request->quy_cach ?? null,
                'don_vi'        => $request->don_vi ?? null,
                'don_gia'       => $request->don_gia,
                'ton_kho'       => $request->ton_kho ?? 0,
                'url_hinh_anh'  => $imageUrl,
                'ma_danh_muc'   => $request->ma_danh_muc
            ];

            $product = SanPham::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Thêm sản phẩm thành công',
                'data' => $product
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm (Admin)
     */
    public function show($id)
    {
        try {
            $product = SanPham::with('danhMuc')->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product
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
     * Cập nhật sản phẩm (Admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $product = SanPham::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            // Validate request
            $request->validate([
                'ten_san_pham' => 'string|max:255',
                'don_gia' => 'numeric|min:1',
                'ton_kho' => 'integer|min:0',
                'ma_danh_muc' => 'integer',
                'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            $data = [];
            if ($request->has('ten_san_pham')) $data['ten_san_pham'] = $request->ten_san_pham;
            if ($request->has('mo_ta')) $data['mo_ta'] = $request->mo_ta ?? null;
            if ($request->has('quy_cach')) $data['quy_cach'] = $request->quy_cach ?? null;
            if ($request->has('don_vi')) $data['don_vi'] = $request->don_vi ?? null;
            if ($request->has('don_gia')) $data['don_gia'] = $request->don_gia;
            if ($request->has('ton_kho')) $data['ton_kho'] = $request->ton_kho;
            if ($request->has('ma_danh_muc')) $data['ma_danh_muc'] = $request->ma_danh_muc;

            // Xử lý upload file mới
            if ($request->hasFile('hinh_anh')) {
                // Xóa file cũ nếu có
                if ($product->url_hinh_anh && file_exists(public_path($product->url_hinh_anh))) {
                    unlink(public_path($product->url_hinh_anh));
                }
                
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);
                $data['url_hinh_anh'] = '/images/products/' . $filename;
            }

            if (!empty($data)) {
                $product->update($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $product
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sản phẩm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sản phẩm (Admin)
     */
    public function destroy($id)
    {
        try {
            $product = SanPham::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa sản phẩm thành công'
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
     * Lấy thống kê dashboard
     */
    public function getStatistics()
    {
        try {
            // Tổng sản phẩm
            $totalProducts = SanPham::count();

            // Tổng đơn hàng
            $totalOrders = DonHang::count();

            // Tổng khách hàng
            $totalUsers = DB::table('tai_khoan')->where('id_vai_tro', 2)->count();

            // Doanh thu hôm nay
            $todayRevenue = DonHang::whereDate('ngay_tao', today())
                ->where('trang_thai', '!=', 'Hủy')
                ->sum('tong_tien');

            // Doanh thu tháng này
            $monthRevenue = DonHang::whereMonth('ngay_tao', now()->month)
                ->whereYear('ngay_tao', now()->year)
                ->where('trang_thai', '!=', 'Hủy')
                ->sum('tong_tien');

            // Top 5 sản phẩm bán chạy
            $topProducts = ChiTietDonHang::select(
                'ma_san_pham',
                DB::raw('SUM(so_luong) as total_quantity'),
                DB::raw('SUM(thanh_tien) as total_revenue')
            )
            ->with('sanPham')
            ->groupBy('ma_san_pham')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

            // Sản phẩm sắp hết hàng (tồn kho < 10)
            $lowStockProducts = SanPham::where('ton_kho', '<', 10)
                ->orderBy('ton_kho', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_products' => $totalProducts,
                    'total_orders' => $totalOrders,
                    'total_users' => $totalUsers,
                    'today_revenue' => $todayRevenue,
                    'month_revenue' => $monthRevenue,
                    'top_products' => $topProducts,
                    'low_stock_products' => $lowStockProducts
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
