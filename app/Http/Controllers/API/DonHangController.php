<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\SanPham;
use App\Models\KhuyenMaiDonHang;

class DonHangController extends Controller
{
    /**
     * Lấy danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = DonHang::with(['khachHang', 'khuyenMaiDonHang', 'chiTietDonHang.sanPham']);

        // Filter theo khách hàng (cho API khách hàng)
        if ($request->has('ma_khach_hang') && $request->ma_khach_hang) {
            $query->where('ma_khach_hang', $request->ma_khach_hang);
        }

        // Filter theo trạng thái
        if ($request->has('trang_thai') && $request->trang_thai) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Filter theo khoảng thời gian
        if ($request->has('tu_ngay') && $request->tu_ngay) {
            $query->whereDate('ngay_lap', '>=', $request->tu_ngay);
        }
        if ($request->has('den_ngay') && $request->den_ngay) {
            $query->whereDate('ngay_lap', '<=', $request->den_ngay);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'ngay_lap');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $donHang = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $donHang,
            'message' => 'Lấy danh sách đơn hàng thành công'
        ]);
    }

    /**
     * Tạo đơn hàng mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ma_khach_hang' => 'required|exists:khach_hang,ma_khach_hang',
            'dia_chi_giao_hang' => 'required|string|max:255',
            'phuong_thuc_thanh_toan' => 'required|string|max:150',
            'ma_khuyen_mai' => 'nullable|string',
            'chi_tiet' => 'required|array|min:1',
            'chi_tiet.*.ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'chi_tiet.*.so_luong' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $tongTien = 0;
            $chiTietDonHang = [];

            // Tính tổng tiền và chuẩn bị dữ liệu chi tiết
            foreach ($request->chi_tiet as $item) {
                $sanPham = SanPham::find($item['ma_san_pham']);
                
                if (!$sanPham) {
                    throw new \Exception("Sản phẩm không tồn tại");
                }

                if ($sanPham->ton_kho < $item['so_luong']) {
                    throw new \Exception("Sản phẩm '{$sanPham->ten_san_pham}' không đủ số lượng trong kho");
                }

                $donGiaLucMua = $sanPham->don_gia;
                $thanhTien = $donGiaLucMua * $item['so_luong'];
                $tongTien += $thanhTien;

                $chiTietDonHang[] = [
                    'ma_san_pham' => $item['ma_san_pham'],
                    'so_luong' => $item['so_luong'],
                    'don_gia_luc_mua' => $donGiaLucMua
                ];
            }

            // Áp dụng mã khuyến mãi nếu có
            $khuyenMaiId = null;
            if ($request->ma_khuyen_mai) {
                $khuyenMai = KhuyenMaiDonHang::where('code', $request->ma_khuyen_mai)
                    ->where('trang_thai', 1)
                    ->where('han_su_dung', '>=', now())
                    ->first();

                if ($khuyenMai) {
                    $tongTien = max(0, $tongTien - $khuyenMai->so_tien_giam);
                    $khuyenMaiId = $khuyenMai->id_km_don_hang;
                }
            }

            // Tạo đơn hàng
            $donHang = DonHang::create([
                'ma_khach_hang' => $request->ma_khach_hang,
                'ngay_lap' => now(),
                'id_km_don_hang_fk' => $khuyenMaiId,
                'tong_tien' => $tongTien,
                'dia_chi_giao_hang' => $request->dia_chi_giao_hang,
                'phuong_thuc_thanh_toan' => $request->phuong_thuc_thanh_toan,
                'trang_thai' => 'cho_xac_nhan'
            ]);

            // Tạo chi tiết đơn hàng và cập nhật tồn kho
            foreach ($chiTietDonHang as $chiTiet) {
                $chiTiet['ma_don_hang'] = $donHang->ma_don_hang;
                ChiTietDonHang::create($chiTiet);

                // Cập nhật tồn kho
                $sanPham = SanPham::find($chiTiet['ma_san_pham']);
                $sanPham->decrement('ton_kho', $chiTiet['so_luong']);
            }

            DB::commit();

            // Load relationships cho response
            $donHang->load(['khachHang', 'khuyenMaiDonHang', 'chiTietDonHang.sanPham']);

            return response()->json([
                'success' => true,
                'data' => $donHang,
                'message' => 'Tạo đơn hàng thành công'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function show($id)
    {
        $donHang = DonHang::with([
            'khachHang',
            'khuyenMaiDonHang',
            'chiTietDonHang.sanPham.danhMuc'
        ])->find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donHang,
            'message' => 'Lấy thông tin đơn hàng thành công'
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'trang_thai' => 'required|in:cho_xac_nhan,da_xac_nhan,dang_chuan_bi,dang_giao,da_giao,da_huy'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $donHang = DonHang::find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        // Kiểm tra logic chuyển trạng thái
        $currentStatus = $donHang->trang_thai;
        $newStatus = $request->trang_thai;

        if ($currentStatus === 'da_giao' || $currentStatus === 'da_huy') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể thay đổi trạng thái đơn hàng đã hoàn thành hoặc đã hủy'
            ], 400);
        }

        try {
            // Nếu hủy đơn hàng, hoàn lại tồn kho
            if ($newStatus === 'da_huy' && $currentStatus !== 'da_huy') {
                foreach ($donHang->chiTietDonHang as $chiTiet) {
                    $sanPham = SanPham::find($chiTiet->ma_san_pham);
                    $sanPham->increment('ton_kho', $chiTiet->so_luong);
                }
            }

            $donHang->update(['trang_thai' => $newStatus]);
            $donHang->load(['khachHang', 'khuyenMaiDonHang', 'chiTietDonHang.sanPham']);

            return response()->json([
                'success' => true,
                'data' => $donHang,
                'message' => 'Cập nhật trạng thái đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id, Request $request)
    {
        $donHang = DonHang::with('chiTietDonHang')->find($id);

        if (!$donHang) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng'
            ], 404);
        }

        if ($donHang->trang_thai === 'da_giao' || $donHang->trang_thai === 'da_huy') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy đơn hàng đã hoàn thành hoặc đã hủy'
            ], 400);
        }

        try {
            // Hoàn lại tồn kho
            foreach ($donHang->chiTietDonHang as $chiTiet) {
                $sanPham = SanPham::find($chiTiet->ma_san_pham);
                $sanPham->increment('ton_kho', $chiTiet->so_luong);
            }

            $donHang->update(['trang_thai' => 'da_huy']);

            return response()->json([
                'success' => true,
                'message' => 'Hủy đơn hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thống kê đơn hàng
     */
    public function statistics(Request $request)
    {
        $query = DonHang::query();

        // Filter theo khoảng thời gian
        if ($request->has('tu_ngay') && $request->tu_ngay) {
            $query->whereDate('ngay_lap', '>=', $request->tu_ngay);
        }
        if ($request->has('den_ngay') && $request->den_ngay) {
            $query->whereDate('ngay_lap', '<=', $request->den_ngay);
        }

        $statistics = [
            'tong_don_hang' => $query->count(),
            'cho_xac_nhan' => $query->clone()->where('trang_thai', 'cho_xac_nhan')->count(),
            'da_xac_nhan' => $query->clone()->where('trang_thai', 'da_xac_nhan')->count(),
            'dang_chuan_bi' => $query->clone()->where('trang_thai', 'dang_chuan_bi')->count(),
            'dang_giao' => $query->clone()->where('trang_thai', 'dang_giao')->count(),
            'da_giao' => $query->clone()->where('trang_thai', 'da_giao')->count(),
            'da_huy' => $query->clone()->where('trang_thai', 'da_huy')->count(),
            'tong_doanh_thu' => $query->clone()->where('trang_thai', 'da_giao')->sum('tong_tien'),
            'doanh_thu_hom_nay' => DonHang::whereDate('ngay_lap', today())
                ->where('trang_thai', 'da_giao')
                ->sum('tong_tien')
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'message' => 'Lấy thống kê đơn hàng thành công'
        ]);
    }
}
