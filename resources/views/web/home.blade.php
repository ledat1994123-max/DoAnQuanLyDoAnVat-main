@extends('layouts.app')

@section('title', 'Trang chủ - Cửa hàng đồ ăn vặt')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-primary text-white rounded p-5 text-center">
                <h1 class="display-4">Chào mừng đến với cửa hàng đồ ăn vặt!</h1>
                <p class="lead">Khám phá hàng trăm món ăn vặt ngon miệng với giá cả hợp lý</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Xem sản phẩm</a>
            </div>
        </div>
    </div>

    <!-- Danh mục sản phẩm -->
    @if($danhMuc->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Danh mục sản phẩm</h2>
            <div class="row">
                @foreach($danhMuc as $category)
                <div class="col-md-4 col-lg-2 mb-3">
                    <a href="{{ route('products.category', $category->ma_danh_muc) }}" class="text-decoration-none">
                        <div class="card text-center h-100 category-card">
                            <div class="card-body">
                                <i class="fas fa-utensils fa-2x text-primary mb-2"></i>
                                <h6 class="card-title">{{ $category->ten_danh_muc }}</h6>
                                <small class="text-muted">{{ $category->san_pham_count }} sản phẩm</small>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Sản phẩm nổi bật -->
    @if($sanPhamNoiBat->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
            <div class="row">
                @foreach($sanPhamNoiBat as $product)
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="{{ $product->url_hinh_anh ?: '/images/no-image.jpg' }}" 
                                 class="card-img-top" alt="{{ $product->ten_san_pham }}" 
                                 style="height: 200px; object-fit: cover;">
                            
                            @if($product->khuyenMaiSanPham->count() > 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                    -{{ $product->khuyenMaiSanPham->first()->phan_tram_giam_gia }}%
                                </span>
                            @endif

                            @if($product->ton_kho <= 5)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                    Sắp hết
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->ten_san_pham }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->mo_ta, 80) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->khuyenMaiSanPham->count() > 0)
                                            <span class="text-decoration-line-through text-muted">
                                                {{ number_format($product->don_gia) }}₫
                                            </span><br>
                                            <span class="fw-bold text-danger">
                                                {{ number_format($product->gia_khuyen_mai) }}₫
                                            </span>
                                        @else
                                            <span class="fw-bold text-primary">
                                                {{ number_format($product->don_gia) }}₫
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $product->don_vi }}</small>
                                </div>
                                
                                <div class="mt-2">
                                    <a href="{{ route('products.show', $product->ma_san_pham) }}" 
                                       class="btn btn-outline-primary btn-sm me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-primary btn-sm add-to-cart" 
                                            data-product-id="{{ $product->ma_san_pham }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center">
                <a href="{{ route('products.index') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>
            </div>
        </div>
    </div>
    @endif

    <!-- Sản phẩm bán chạy -->
    @if($sanPhamBanChay->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Sản phẩm bán chạy</h2>
            <div class="row">
                @foreach($sanPhamBanChay as $product)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card product-card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ $product->url_hinh_anh ?: '/images/no-image.jpg' }}" 
                                     class="img-fluid rounded-start h-100" alt="{{ $product->ten_san_pham }}"
                                     style="object-fit: cover;">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-3">
                                    <h6 class="card-title">{{ $product->ten_san_pham }}</h6>
                                    <p class="card-text fw-bold text-primary">
                                        {{ number_format($product->don_gia) }}₫
                                    </p>
                                    <a href="{{ route('products.show', $product->ma_san_pham) }}" 
                                       class="btn btn-sm btn-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
.product-card:hover {
    transform: translateY(-5px);
    transition: transform 0.2s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Thêm sản phẩm vào giỏ hàng
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: '{{ route("ajax.add-to-cart") }}',
            method: 'POST',
            data: {
                ma_san_pham: productId,
                so_luong: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Cập nhật số lượng giỏ hàng
                    $('#cart-count').text(response.cart_count);
                    
                    // Hiển thị thông báo
                    $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" 
                             style="top: 20px; right: 20px; z-index: 9999;">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
                    // Tự động ẩn thông báo sau 3 giây
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 3000);
                }
            },
            error: function(xhr) {
                var response = JSON.parse(xhr.responseText);
                alert(response.message || 'Có lỗi xảy ra');
            }
        });
    });
});
</script>
@endsection