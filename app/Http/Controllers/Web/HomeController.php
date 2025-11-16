<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy sản phẩm nổi bật
        $sanPhamNoiBat = SanPham::with(['danhMuc', 'khuyenMaiSanPham' => function($q) {
            $q->where('ngay_bat_dau', '<=', now())
              ->where('ngay_ket_thuc', '>=', now());
        }])
        ->where('ton_kho', '>', 0)
        ->orderBy('created_at', 'desc')
        ->limit(8)
        ->get();

        // Lấy danh mục
        $danhMuc = DanhMuc::withCount(['sanPham' => function($q) {
            $q->where('ton_kho', '>', 0);
        }])
        ->orderBy('created_at', 'asc')
        ->get();

        // Lấy sản phẩm bán chạy (giả lập dựa trên số lượng đơn hàng)
        $sanPhamBanChay = SanPham::with(['danhMuc', 'khuyenMaiSanPham'])
            ->where('ton_kho', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('web.home', compact('sanPhamNoiBat', 'danhMuc', 'sanPhamBanChay'));
    }

    /**
     * Trang giới thiệu
     */
    public function about()
    {
        return view('web.about');
    }

    /**
     * Trang liên hệ
     */
    public function contact()
    {
        return view('web.contact');
    }

    /**
     * Xử lý form liên hệ
     */
    public function postContact(Request $request)
    {
        $request->validate([
            'ten' => 'required|string|max:150',
            'email' => 'required|email|max:100',
            'chu_de' => 'required|string|max:200',
            'noi_dung' => 'required|string|max:1000'
        ], [
            'ten.required' => 'Vui lòng nhập tên của bạn',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'chu_de.required' => 'Vui lòng nhập chủ đề',
            'noi_dung.required' => 'Vui lòng nhập nội dung'
        ]);

        // Lưu thông tin liên hệ vào database hoặc gửi email
        // Tạm thời chỉ redirect về với thông báo thành công
        
        return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
