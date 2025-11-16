@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                    </div>
                    <h5 class="card-title">{{ $taiKhoan->khachHang->ten_khach_hang }}</h5>
                    <p class="text-muted">{{ $taiKhoan->khachHang->email }}</p>
                    <small class="text-muted">
                        Thành viên từ {{ $taiKhoan->created_at->format('d/m/Y') }}
                    </small>
                </div>
            </div>

            <div class="list-group mt-3">
                <a href="#profile-tab" class="list-group-item list-group-item-action active" 
                   data-bs-toggle="tab" data-bs-target="#profile-tab">
                    <i class="fas fa-user me-2"></i> Thông tin cá nhân
                </a>
                <a href="#password-tab" class="list-group-item list-group-item-action" 
                   data-bs-toggle="tab" data-bs-target="#password-tab">
                    <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                </a>
                <a href="#orders-tab" class="list-group-item list-group-item-action" 
                   data-bs-toggle="tab" data-bs-target="#orders-tab">
                    <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- Tab thông tin cá nhân -->
                <div class="tab-pane fade show active" id="profile-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin cá nhân</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ten_khach_hang" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('ten_khach_hang') is-invalid @enderror" 
                                                   id="ten_khach_hang" name="ten_khach_hang" 
                                                   value="{{ old('ten_khach_hang', $taiKhoan->khachHang->ten_khach_hang) }}" required>
                                            @error('ten_khach_hang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" 
                                                   value="{{ old('email', $taiKhoan->khachHang->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                            <input type="tel" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                                   id="so_dien_thoai" name="so_dien_thoai" 
                                                   value="{{ old('so_dien_thoai', $taiKhoan->khachHang->so_dien_thoai) }}">
                                            @error('so_dien_thoai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                                            <input type="text" class="form-control" id="ten_dang_nhap" 
                                                   value="{{ $taiKhoan->ten_dang_nhap }}" readonly>
                                            <small class="text-muted">Tên đăng nhập không thể thay đổi</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                                    <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                              id="dia_chi" name="dia_chi" rows="3">{{ old('dia_chi', $taiKhoan->khachHang->dia_chi) }}</textarea>
                                    @error('dia_chi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Cập nhật thông tin
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab đổi mật khẩu -->
                <div class="tab-pane fade" id="password-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-lock"></i> Đổi mật khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="mat_khau_cu" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('mat_khau_cu') is-invalid @enderror" 
                                           id="mat_khau_cu" name="mat_khau_cu">
                                    @error('mat_khau_cu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mat_khau_moi" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('mat_khau_moi') is-invalid @enderror" 
                                           id="mat_khau_moi" name="mat_khau_moi">
                                    @error('mat_khau_moi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                </div>

                                <div class="mb-3">
                                    <label for="mat_khau_moi_confirmation" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="mat_khau_moi_confirmation" name="mat_khau_moi_confirmation">
                                </div>

                                <!-- Hidden fields để giữ thông tin cá nhân -->
                                <input type="hidden" name="ten_khach_hang" value="{{ $taiKhoan->khachHang->ten_khach_hang }}">
                                <input type="hidden" name="email" value="{{ $taiKhoan->khachHang->email }}">
                                <input type="hidden" name="so_dien_thoai" value="{{ $taiKhoan->khachHang->so_dien_thoai }}">
                                <input type="hidden" name="dia_chi" value="{{ $taiKhoan->khachHang->dia_chi }}">

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key"></i> Đổi mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab đơn hàng -->
                <div class="tab-pane fade" id="orders-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <h5>Chưa có đơn hàng nào</h5>
                                <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i> Bắt đầu mua sắm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Xử lý tab navigation
    $('.list-group-item[data-bs-toggle="tab"]').on('click', function(e) {
        e.preventDefault();
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
        
        var target = $(this).attr('data-bs-target');
        $('.tab-pane').removeClass('show active');
        $(target).addClass('show active');
    });
});
</script>
@endsection