<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Hệ thống không khả dụng' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            margin: 20px;
        }
        .error-icon {
            font-size: 80px;
            color: #ff6b6b;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 28px;
        }
        .error-message {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
        }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .steps h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .steps ol {
            color: #34495e;
            line-height: 1.8;
        }
        .steps code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e74c3c;
        }
        .retry-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }
        .retry-btn:hover {
            transform: translateY(-2px);
        }
        .api-status {
            margin-top: 20px;
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Hệ thống chưa sẵn sàng</h1>
        <div class="error-message">
            {{ $message ?? 'API Backend chưa được khởi động. Website này hoạt động dựa trên API và cần API được khởi chạy trước.' }}
        </div>

        <div class="steps">
            <h3>🚀 Để khởi động hệ thống:</h3>
            <ol>
                <li>Mở terminal/command prompt</li>
                <li>Điều hướng đến thư mục dự án: <code>cd c:\xampp\htdocs\Food-Ordering-System-main</code></li>
                <li>Khởi động Laravel server: <code>php artisan serve</code></li>
                <li>Đảm bảo API chạy tại: <code>http://127.0.0.1:8000/api</code></li>
                <li>Reload trang này để tiếp tục</li>
            </ol>
        </div>

        <div class="api-status">
            <strong>🔍 Kiểm tra API:</strong><br>
            Hệ thống đang tìm kiếm API tại: <code>{{ url('/api/test') }}</code>
        </div>

        <a href="{{ url()->current() }}" class="retry-btn">🔄 Thử lại</a>
    </div>

    <script>
        // Auto retry mỗi 5 giây
        setTimeout(() => {
            fetch('{{ url('/api/test') }}')
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.log('API chưa sẵn sàng, thử lại sau 5 giây...');
                });
        }, 5000);
    </script>
</body>
</html>