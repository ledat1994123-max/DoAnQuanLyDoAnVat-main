@extends('layouts.app')

@section('title', 'Giới thiệu')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1>Về chúng tôi</h1>
                <p class="lead">Cửa hàng đồ ăn vặt uy tín với hơn 10 năm kinh nghiệm</p>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-6">
            <img src="https://via.placeholder.com/600x400?text=Cửa+hàng+đồ+ăn+vặt" 
                 alt="Cửa hàng" class="img-fluid rounded shadow">
        </div>
        <div class="col-lg-6">
            <h3>Câu chuyện của chúng tôi</h3>
            <p>Được thành lập từ năm 2014, cửa hàng đồ ăn vặt của chúng tôi đã trở thành địa chỉ quen thuộc của nhiều khách hàng yêu thích các món ăn vặt ngon, chất lượng và an toàn.</p>
            
            <p>Chúng tôi cam kết mang đến những sản phẩm tươi ngon nhất với giá cả hợp lý, phục vụ tận tình và chu đáo để khách hàng có những trải nghiệm mua sắm tuyệt vời nhất.</p>

            <div class="row mt-4">
                <div class="col-6">
                    <div class="text-center">
                        <h4 class="text-primary">500+</h4>
                        <p>Sản phẩm đa dạng</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center">
                        <h4 class="text-primary">10,000+</h4>
                        <p>Khách hàng hài lòng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-center mb-4">Tại sao chọn chúng tôi?</h3>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h5>Chất lượng đảm bảo</h5>
            <p>Tất cả sản phẩm đều được kiểm tra kỹ lưỡng về chất lượng và nguồn gốc xuất xứ trước khi bán ra.</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
            <h5>Giao hàng nhanh chóng</h5>
            <p>Dịch vụ giao hàng tận nơi trong vòng 24h với đội ngũ shipper chuyên nghiệp.</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-headset fa-3x text-info mb-3"></i>
            <h5>Hỗ trợ 24/7</h5>
            <p>Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4 text-center">
                <h4>Liên hệ với chúng tôi</h4>
                <p class="mb-3">Hãy ghé thăm cửa hàng hoặc liên hệ để được tư vấn!</p>
                <a href="{{ route('contact') }}" class="btn btn-primary me-2">Liên hệ ngay</a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Xem sản phẩm</a>
            </div>
        </div>
    </div>
</div>
@endsection