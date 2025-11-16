@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">
                <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
            </h2>
        </div>
    </div>

    @if(!empty($cart) && count($cart) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sản phẩm trong giỏ hàng ({{ count($cart) }} sản phẩm)</h5>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('cart.update') }}" id="cart-form">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th class="text-center">Đơn giá</th>
                                            <th class="text-center">Số lượng</th>
                                            <th class="text-center">Thành tiền</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart as $maSanPham => $item)
                                        <tr class="cart-item" data-product-id="{{ $maSanPham }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['url_hinh_anh'] ?: 'https://via.placeholder.com/60x60?text=No+Image' }}" 
                                                         alt="{{ $item['ten_san_pham'] }}" 
                                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1">{{ $item['ten_san_pham'] }}</h6>
                                                        <small class="text-muted">Mã: {{ $maSanPham }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold text-primary">{{ number_format($item['don_gia']) }}₫</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="input-group" style="width: 120px; margin: 0 auto;">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-decrease">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm text-center quantity-input" 
                                                           name="quantities[{{ $maSanPham }}]" 
                                                           value="{{ $item['so_luong'] }}" 
                                                           min="1" max="999"
                                                           data-price="{{ $item['don_gia'] }}">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm qty-increase">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold item-total">{{ number_format($item['don_gia'] * $item['so_luong']) }}₫</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-item" 
                                                        data-product-id="{{ $maSanPham }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-sync"></i> Cập nhật giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="subtotal">{{ number_format($totalAmount) }}₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span class="text-muted">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-primary" id="total">{{ number_format($totalAmount) }}₫</strong>
                        </div>
                        
                        @if(session('user_id'))
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" onclick="checkout()">
                                    <i class="fas fa-credit-card"></i> Đặt hàng
                                </button>
                            </div>
                        @else
                            <div class="d-grid gap-2">
                                <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập để đặt hàng
                                </a>
                            </div>
                            <small class="text-muted d-block text-center mt-2">
                                Bạn cần đăng nhập để có thể đặt hàng
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h4>Giỏ hàng của bạn đang trống</h4>
                    <p class="text-muted">Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Bắt đầu mua sắm
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Tăng/giảm số lượng
    $('.qty-increase').click(function() {
        var input = $(this).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        updateItemTotal($(this).closest('.cart-item'));
    });

    $('.qty-decrease').click(function() {
        var input = $(this).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
            updateItemTotal($(this).closest('.cart-item'));
        }
    });

    // Cập nhật khi thay đổi số lượng trực tiếp
    $('.quantity-input').change(function() {
        var val = parseInt($(this).val());
        if (val < 1) {
            $(this).val(1);
        }
        updateItemTotal($(this).closest('.cart-item'));
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $('.remove-item').click(function() {
        var productId = $(this).data('product-id');
        var row = $(this).closest('.cart-item');
        
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            $.ajax({
                url: '{{ route("ajax.remove-from-cart", ":id") }}'.replace(':id', productId),
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                            updateCartSummary();
                            $('#cart-count').text(response.cart_count);
                            
                            // Nếu giỏ hàng trống, reload trang
                            if ($('.cart-item').length === 0) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                }
            });
        }
    });

    function updateItemTotal(row) {
        var price = parseFloat(row.find('.quantity-input').data('price'));
        var quantity = parseInt(row.find('.quantity-input').val());
        var total = price * quantity;
        
        row.find('.item-total').text(numberFormat(total) + '₫');
        updateCartSummary();
    }

    function updateCartSummary() {
        var total = 0;
        $('.item-total').each(function() {
            var itemTotal = parseFloat($(this).text().replace(/[₫,]/g, ''));
            total += itemTotal;
        });
        
        $('#subtotal').text(numberFormat(total) + '₫');
        $('#total').text(numberFormat(total) + '₫');
    }

    function numberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});

function checkout() {
    alert('Tính năng đặt hàng sẽ được phát triển trong phiên bản tiếp theo!');
}
</script>
@endsection