<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\TaiKhoan;
use App\Models\KhachHang;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            return redirect()->route('home');
        }
        
        return view('web.auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string'
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu'
        ]);

        $taiKhoan = TaiKhoan::where('ten_dang_nhap', $request->ten_dang_nhap)->first();

        if (!$taiKhoan || !Hash::check($request->mat_khau, $taiKhoan->mat_khau)) {
            return back()->withErrors([
                'login_error' => 'Tên đăng nhập hoặc mật khẩu không đúng'
            ])->withInput();
        }

        // Lưu thông tin đăng nhập vào session
        Session::put('user_id', $taiKhoan->id_tai_khoan);
        Session::put('user_role', $taiKhoan->id_vai_tro);
        
        if ($taiKhoan->id_vai_tro == 2) { // Khách hàng
            $khachHang = $taiKhoan->khachHang;
            Session::put('user_info', [
                'id' => $khachHang->ma_khach_hang,
                'ten' => $khachHang->ten_khach_hang,
                'email' => $khachHang->email
            ]);
        } else { // Admin/Nhân viên
            $quanTriVien = $taiKhoan->quanTriVien;
            Session::put('user_info', [
                'id' => $quanTriVien->ma_quan_tri_vien,
                'ten' => $quanTriVien->ten_quan_tri_vien,
                'email' => $quanTriVien->email
            ]);
        }

        // Redirect về trang đã định hoặc trang chủ
        $redirectTo = $request->get('redirect', route('home'));
        
        return redirect($redirectTo)->with('success', 'Đăng nhập thành công!');
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegister()
    {
        if ($this->isLoggedIn()) {
            return redirect()->route('home');
        }
        
        return view('web.auth.register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string|max:50|unique:tai_khoan',
            'mat_khau' => 'required|string|min:6|confirmed',
            'ten_khach_hang' => 'required|string|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'dia_chi' => 'nullable|string|max:150'
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã được sử dụng',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp',
            'ten_khach_hang.required' => 'Vui lòng nhập tên đầy đủ',
            'email.email' => 'Email không hợp lệ'
        ]);

        try {
            // Tạo tài khoản
            $taiKhoan = TaiKhoan::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'mat_khau' => $request->mat_khau,
                'id_vai_tro' => 2, // Khách hàng
                'ngay_tao' => now()
            ]);

            // Tạo thông tin khách hàng
            $khachHang = KhachHang::create([
                'id_tai_khoan' => $taiKhoan->id_tai_khoan,
                'ten_khach_hang' => $request->ten_khach_hang,
                'so_dien_thoai' => $request->so_dien_thoai,
                'email' => $request->email,
                'dia_chi' => $request->dia_chi
            ]);

            // Tự động đăng nhập sau khi đăng ký
            Session::put('user_id', $taiKhoan->id_tai_khoan);
            Session::put('user_role', $taiKhoan->id_vai_tro);
            Session::put('user_info', [
                'id' => $khachHang->ma_khach_hang,
                'ten' => $khachHang->ten_khach_hang,
                'email' => $khachHang->email
            ]);

            return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với cửa hàng.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'register_error' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.'
            ])->withInput();
        }
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * Hiển thị trang profile
     */
    public function profile()
    {
        if (!$this->isLoggedIn() || Session::get('user_role') != 2) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        $taiKhoan = TaiKhoan::with('khachHang')->find($userId);
        
        return view('web.auth.profile', compact('taiKhoan'));
    }

    /**
     * Cập nhật profile
     */
    public function updateProfile(Request $request)
    {
        if (!$this->isLoggedIn() || Session::get('user_role') != 2) {
            return redirect()->route('login');
        }

        $request->validate([
            'ten_khach_hang' => 'required|string|max:150',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'dia_chi' => 'nullable|string|max:150',
            'mat_khau_cu' => 'nullable|string',
            'mat_khau_moi' => 'nullable|string|min:6|confirmed'
        ], [
            'ten_khach_hang.required' => 'Vui lòng nhập tên đầy đủ',
            'email.email' => 'Email không hợp lệ',
            'mat_khau_moi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu mới không khớp'
        ]);

        try {
            $userId = Session::get('user_id');
            $taiKhoan = TaiKhoan::with('khachHang')->find($userId);
            
            // Cập nhật thông tin khách hàng
            $taiKhoan->khachHang->update([
                'ten_khach_hang' => $request->ten_khach_hang,
                'so_dien_thoai' => $request->so_dien_thoai,
                'email' => $request->email,
                'dia_chi' => $request->dia_chi
            ]);

            // Cập nhật mật khẩu nếu có
            if ($request->filled('mat_khau_moi')) {
                if (!$request->filled('mat_khau_cu') || !Hash::check($request->mat_khau_cu, $taiKhoan->mat_khau)) {
                    return back()->withErrors(['mat_khau_cu' => 'Mật khẩu cũ không đúng']);
                }
                
                $taiKhoan->update(['mat_khau' => $request->mat_khau_moi]);
            }

            // Cập nhật session
            Session::put('user_info', [
                'id' => $taiKhoan->khachHang->ma_khach_hang,
                'ten' => $taiKhoan->khachHang->ten_khach_hang,
                'email' => $taiKhoan->khachHang->email
            ]);

            return back()->with('success', 'Cập nhật thông tin thành công!');

        } catch (\Exception $e) {
            return back()->withErrors(['update_error' => 'Có lỗi xảy ra khi cập nhật thông tin']);
        }
    }

    /**
     * Kiểm tra đã đăng nhập chưa
     */
    private function isLoggedIn()
    {
        return Session::has('user_id');
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'id' => Session::get('user_id'),
            'role' => Session::get('user_role'),
            'info' => Session::get('user_info')
        ];
    }
}
