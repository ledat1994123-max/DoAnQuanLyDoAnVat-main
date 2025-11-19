<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SanPhamController;
use App\Http\Controllers\API\DanhMucController;
use App\Http\Controllers\API\DonHangController;
use App\Http\Controllers\API\KhachHangController;
use App\Http\Controllers\API\Admin\CategoryAdminController;
use App\Http\Controllers\API\Admin\TaiKhoanAdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
});

// Public routes - không cần authentication
Route::prefix('public')->group(function () {
    // Danh mục
    Route::get('danh-muc', [DanhMucController::class, 'index']);
    Route::get('danh-muc/{id}', [DanhMucController::class, 'show']);
    Route::get('danh-muc/{id}/san-pham', [DanhMucController::class, 'products']);
    
    // Sản phẩm
    Route::get('san-pham', [SanPhamController::class, 'index']);
    Route::get('san-pham/noi-bat', [SanPhamController::class, 'featured']);
    Route::get('san-pham/{id}', [SanPhamController::class, 'show']);
    Route::get('san-pham/{id}/lien-quan', [SanPhamController::class, 'related']);
});

// Protected routes - cần authentication
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Khách hàng routes
    Route::prefix('khach-hang')->group(function () {
        Route::apiResource('/', KhachHangController::class);
        Route::get('profile', [KhachHangController::class, 'profile']);
        Route::put('profile', [KhachHangController::class, 'updateProfile']);
    });
    
    // Đơn hàng routes cho khách hàng
    Route::prefix('don-hang')->group(function () {
        Route::get('/', [DonHangController::class, 'index']);
        Route::post('/', [DonHangController::class, 'store']);
        Route::get('{id}', [DonHangController::class, 'show']);
        Route::put('{id}/huy', [DonHangController::class, 'cancel']);
    });
});

// Admin routes - cần authentication và quyền admin
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    
    // Quản lý danh mục
    Route::prefix('danh-muc')->group(function () {
        Route::post('/', [DanhMucController::class, 'store']);
        Route::put('{id}', [DanhMucController::class, 'update']);
        Route::delete('{id}', [DanhMucController::class, 'destroy']);
    });
    
    // Quản lý sản phẩm
    Route::prefix('san-pham')->group(function () {
        Route::post('/', [SanPhamController::class, 'store']);
        Route::put('{id}', [SanPhamController::class, 'update']);
        Route::delete('{id}', [SanPhamController::class, 'destroy']);
    });
    
    // Quản lý đơn hàng
    Route::prefix('don-hang')->group(function () {
        Route::get('/', [DonHangController::class, 'index']);
        Route::get('thong-ke', [DonHangController::class, 'statistics']);
        Route::get('{id}', [DonHangController::class, 'show']);
        Route::put('{id}/trang-thai', [DonHangController::class, 'updateStatus']);
    });
    
    // Quản lý khách hàng
    Route::prefix('khach-hang')->group(function () {
        Route::get('/', [KhachHangController::class, 'index']);
        Route::get('{id}', [KhachHangController::class, 'show']);
        Route::put('{id}', [KhachHangController::class, 'update']);
        Route::delete('{id}', [KhachHangController::class, 'destroy']);
    });
});

// API test route
Route::get('test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API Food Ordering System hoạt động tốt!',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Admin Category API routes
Route::prefix("admin")->group(function () {
    Route::get("/categories", [CategoryAdminController::class, "index"]);
    Route::post("/categories", [CategoryAdminController::class, "store"]);
    Route::put("/categories/{id}", [CategoryAdminController::class, "update"]);
    Route::delete("/categories/{id}", [CategoryAdminController::class, "destroy"]);
});

Route::prefix('admin')->group(function () {
    Route::get('/tai-khoan', [TaiKhoanAdminController::class, 'index']);
    Route::post('/tai-khoan', [TaiKhoanAdminController::class, 'store']);
});

