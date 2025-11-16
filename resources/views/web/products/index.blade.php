@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Tất cả sản phẩm</h2>
        </div>
    </div>

    <!-- Filter và tìm kiếm -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="danh_muc">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($danhMuc as $category)
                                        <option value="{{ $category->ma_danh_muc }}" 
                                                {{ request('danh_muc') == $category->ma_danh_muc ? 'selected' : '' }}>
                                            {{ $category->ten_danh_muc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="sort_by">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="gia_tang" {{ request('sort_by') == 'gia_tang' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="gia_giam" {{ request('sort_by') == 'gia_giam' ? 'selected' : '' }}>Giá giảm dần</option>
                                    <option value="ten" {{ request('sort_by') == 'ten' ? 'selected' : '' }}>Tên A-Z</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    @if($sanPham->count() > 0)
        <div class="row">
            @foreach($sanPham as $product)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ $product->url_hinh_anh ?: 'https://via.placeholder.com/300x200?text=No+Image' }}" 
                             class="card-img-top" alt="{{ $product->ten_san_pham }}" 
                             style="height: 200px; object-fit: cover;">
                        
                        @if($product->khuyenMaiSanPham && $product->khuyenMaiSanPham->count() > 0)
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
                        <p class="card-text text-muted small">{{ Str::limit($product->mo_ta, 60) }}</p>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag"></i> {{ $product->danhMuc->ten_danh_muc }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($product->khuyenMaiSanPham && $product->khuyenMaiSanPham->count() > 0)
                                        @php
                                            $khuyenMai = $product->khuyenMaiSanPham->first();
                                            $giaGiam = $product->don_gia * ($khuyenMai->phan_tram_giam_gia / 100);
                                            $giaSauGiam = $product->don_gia - $giaGiam;
                                        @endphp
                                        <span class="text-decoration-line-through text-muted small">
                                            {{ number_format($product->don_gia) }}₫
                                        </span><br>
                                        <span class="fw-bold text-danger">
                                            {{ number_format($giaSauGiam) }}₫
                                        </span>
                                    @else
                                        <span class="fw-bold text-primary">
                                            {{ number_format($product->don_gia) }}₫
                                        </span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $product->don_vi }}</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->ma_san_pham) }}" 
                                   class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                                <button class="btn btn-primary btn-sm add-to-cart" 
                                        data-product-id="{{ $product->ma_san_pham }}">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                            
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-box"></i> Còn {{ $product->ton_kho }} {{ $product->don_vi }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $sanPham->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Không tìm thấy sản phẩm nào</h5>
                    <p class="text-muted">Hãy thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Xem tất cả sản phẩm</a>
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
                    $('#cart-count').text(response.cart_count);
                    
                    $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" 
                             style="top: 70px; right: 20px; z-index: 9999;">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                    
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