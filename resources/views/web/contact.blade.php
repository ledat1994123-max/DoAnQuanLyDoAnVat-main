@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1>Liên hệ với chúng tôi</h1>
                <p class="lead">Chúng tôi luôn sẵn sàng hỗ trợ và lắng nghe ý kiến của bạn</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope"></i> Gửi tin nhắn cho chúng tôi</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('contact.post') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                                           id="ten" name="ten" value="{{ old('ten') }}" required>
                                    @error('ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="chu_de" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('chu_de') is-invalid @enderror" 
                                   id="chu_de" name="chu_de" value="{{ old('chu_de') }}" required>
                            @error('chu_de')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="noi_dung" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('noi_dung') is-invalid @enderror" 
                                      id="noi_dung" name="noi_dung" rows="5" required>{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin liên hệ</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-map-marker-alt text-primary"></i> Địa chỉ</h6>
                        <p class="text-muted">123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
                    </div>

                    <div class="mb-3">
                        <h6><i class="fas fa-phone text-success"></i> Điện thoại</h6>
                        <p class="text-muted">
                            <a href="tel:0123456789" class="text-decoration-none">0123-456-789</a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6><i class="fas fa-envelope text-info"></i> Email</h6>
                        <p class="text-muted">
                            <a href="mailto:info@cuahang.com" class="text-decoration-none">info@cuahang.com</a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6><i class="fas fa-clock text-warning"></i> Giờ mở cửa</h6>
                        <p class="text-muted mb-1">Thứ 2 - Thứ 6: 8:00 - 22:00</p>
                        <p class="text-muted mb-1">Thứ 7 - Chủ nhật: 9:00 - 23:00</p>
                    </div>

                    <hr>

                    <div>
                        <h6><i class="fas fa-share-alt text-primary"></i> Theo dõi chúng tôi</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle"></i> Câu hỏi thường gặp</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    Làm thế nào để đặt hàng?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Bạn có thể đặt hàng trực tuyến qua website hoặc gọi điện trực tiếp đến hotline của chúng tôi.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Thời gian giao hàng?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Chúng tôi giao hàng trong vòng 1-2 giờ đối với khu vực nội thành và 2-4 giờ đối với ngoại thành.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Chính sách đổi trả?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Chúng tôi hỗ trợ đổi trả trong vòng 24h nếu sản phẩm có vấn đề về chất lượng.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection