# 🍔 Food Ordering System - API & Frontend

Hệ thống đặt món ăn với kiến trúc tách biệt API và Frontend

## 🚀 Cách chạy hệ thống

### **Phương án 1: Chạy API và Web riêng biệt (Khuyến nghị)**

#### 1. Khởi động API Server (Port 8001)
```bash
# Cách 1: Sử dụng script
start-api.bat

# Cách 2: Manual  
php artisan serve --port=8001
```

#### 2. Khởi động Web Frontend (Port 8000)
```bash
# Cách 1: Sử dụng script
start-web.bat

# Cách 2: Manual
php artisan serve --port=8000
```

### **Phương án 2: Frontend thuần HTML (Không cần Laravel)**

Truy cập trực tiếp: `http://127.0.0.1:8001/frontend.html`

## 🌐 Các URLs quan trọng

### **API Server (Port 8001)**
- **API Base**: http://127.0.0.1:8001/api/
- **API Documentation**: http://127.0.0.1:8001/api-docs.html
- **Test API**: http://127.0.0.1:8001/api/test

### **Web Frontend (Port 8000)**  
- **Trang chủ**: http://127.0.0.1:8000
- **Frontend HTML**: http://127.0.0.1:8000/frontend.html

## 📋 API Endpoints chính

### **Public Endpoints (Không cần authentication)**
```
GET  /api/test                          - Test API connection
GET  /api/public/danh-muc              - Danh sách danh mục  
GET  /api/public/danh-muc/{id}         - Chi tiết danh mục
GET  /api/public/danh-muc/{id}/san-pham - Sản phẩm theo danh mục
GET  /api/public/san-pham              - Danh sách sản phẩm
GET  /api/public/san-pham/noi-bat      - Sản phẩm nổi bật
GET  /api/public/san-pham/{id}         - Chi tiết sản phẩm
GET  /api/public/san-pham/{id}/lien-quan - Sản phẩm liên quan
```

### **Authentication Endpoints**
```
POST /api/auth/register  - Đăng ký
POST /api/auth/login     - Đăng nhập  
POST /api/auth/logout    - Đăng xuất
GET  /api/auth/profile   - Thông tin user
```

### **Protected Endpoints (Cần authentication)**
```
GET  /api/don-hang       - Danh sách đơn hàng
POST /api/don-hang       - Tạo đơn hàng mới
GET  /api/don-hang/{id}  - Chi tiết đơn hàng
```

## 🛠️ Cấu hình Database

### **1. Tạo Database**
```sql
CREATE DATABASE laravel;
```

### **2. Cấu hình .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### **3. Chạy Migration & Seeder**
```bash
php artisan migrate
php artisan db:seed
```

## 🔧 Tính năng Frontend

### **✅ Đã hoàn thành:**
- 🏠 Trang chủ với hero section
- 📂 Hiển thị danh mục sản phẩm
- 🛍️ Danh sách sản phẩm nổi bật
- 🔍 Tìm kiếm sản phẩm  
- 👁️ Xem chi tiết sản phẩm
- 🛒 Giỏ hàng (LocalStorage)
- 📱 Responsive design (Bootstrap 5)
- ⚡ Loading states và error handling
- 🎯 Real-time API calls

### **🔮 Có thể mở rộng:**
- 🔐 Đăng nhập/Đăng ký
- 💳 Thanh toán
- 📋 Quản lý đơn hàng
- ⭐ Đánh giá sản phẩm
- 🎫 Khuyến mãi

## 📖 Ví dụ sử dụng API

### **JavaScript/Fetch**
```javascript
// Cấu hình API base URL
const API_BASE_URL = 'http://127.0.0.1:8001/api';

// Lấy danh sách sản phẩm
async function getProducts() {
    try {
        const response = await fetch(`${API_BASE_URL}/public/san-pham`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}

// Đăng nhập
async function login(username, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                ten_dang_nhap: username,
                mat_khau: password
            })
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Login error:', error);
    }
}
```

### **cURL**
```bash
# Test API
curl http://127.0.0.1:8001/api/test

# Lấy sản phẩm
curl http://127.0.0.1:8001/api/public/san-pham

# Lấy danh mục  
curl http://127.0.0.1:8001/api/public/danh-muc

# Đăng nhập
curl -X POST http://127.0.0.1:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"ten_dang_nhap":"admin","mat_khau":"password"}'
```

## 🏗️ Kiến trúc hệ thống

```
┌─────────────────────┐    HTTP API Calls    ┌─────────────────────┐
│   Frontend Web      │ ────────────────────► │   Backend API       │
│   (Port 8000)       │                       │   (Port 8001)       │
│                     │ ◄──────────────────── │                     │
│ - HTML/CSS/JS       │    JSON Responses     │ - Laravel API       │
│ - Bootstrap UI      │                       │ - MySQL Database    │
│ - Shopping Cart     │                       │ - Business Logic    │
│ - Product Catalog   │                       │ - Authentication    │
└─────────────────────┘                       └─────────────────────┘
```

## 🔒 Authentication

API sử dụng Laravel Sanctum cho authentication:

```javascript
// Lưu token sau khi đăng nhập
localStorage.setItem('auth_token', response.token);

// Sử dụng token trong request
fetch(`${API_BASE_URL}/protected-endpoint`, {
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
    }
});
```

## 🚨 Troubleshooting

### **Lỗi CORS**
Nếu gặp lỗi CORS, đảm bảo file `config/cors.php` có:
```php
'allowed_origins' => ['*'],
```

### **Lỗi Database**
```bash
# Reset database
php artisan migrate:fresh --seed
```

### **Lỗi API không load**
1. Kiểm tra API server có chạy không: http://127.0.0.1:8001/api/test
2. Kiểm tra XAMPP MySQL có start không
3. Kiểm tra file .env database config

## 👨‍💻 Development

### **Cấu trúc project:**
```
├── app/Http/Controllers/API/     # API Controllers
├── app/Models/                   # Eloquent Models  
├── database/migrations/          # Database Migrations
├── database/seeders/            # Database Seeders
├── routes/api.php               # API Routes
├── routes/web.php               # Web Routes
├── public/frontend.html         # Frontend HTML
├── public/api-docs.html         # API Documentation
├── start-api.bat               # API Server Script
└── start-web.bat               # Web Server Script
```

### **Model Relationships:**
- `DanhMuc` ↔ `SanPham` (One-to-Many)
- `SanPham` ↔ `KhuyenMaiSanPham` (One-to-Many)
- `SanPham` ↔ `ChiTietDonHang` (One-to-Many)
- `TaiKhoan` ↔ `KhachHang` (One-to-One)

## 📞 Support

Nếu gặp vấn đề, kiểm tra:
1. **API Server status**: http://127.0.0.1:8001/api/test
2. **Database connection**: `php artisan migrate:status`
3. **Error logs**: `storage/logs/laravel.log`

---

**© 2025 Food Ordering System - Kiến trúc API hiện đại**# DoAnQuanLyBanDoAnVat
