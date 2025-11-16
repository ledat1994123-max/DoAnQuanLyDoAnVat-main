@echo off
echo Starting Food Ordering System API Server...
echo API will be available at: http://127.0.0.1:8001
echo.
echo Available endpoints:
echo - API Base: http://127.0.0.1:8001/api/
echo - Test: http://127.0.0.1:8001/api/test
echo - Products: http://127.0.0.1:8001/api/public/san-pham
echo - Categories: http://127.0.0.1:8001/api/public/danh-muc
echo.
php artisan serve --port=8001