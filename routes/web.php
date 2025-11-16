<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Hybrid Mode (API + Frontend)
|--------------------------------------------------------------------------
*/

// Frontend routes
Route::get('/', function () {
    return redirect('/frontend.html');
})->name('home');

Route::get('/frontend', function () {
    return redirect('/frontend.html');
});

// API Status and Documentation
Route::get('/api-status', function () {
    return response()->json([
        'status' => 'API Available', 
        'timestamp' => now(),
        'endpoints' => [
            'categories' => url('/api/public/danh-muc'),
            'products' => url('/api/public/san-pham'),
            'featured' => url('/api/public/san-pham/noi-bat'),
        ]
    ]);
});

Route::get('/docs', function () {
    return redirect('/api-docs.html');
});

// Fallback for undefined routes
Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found. Available endpoints:',
        'frontend' => url('/frontend.html'),
        'api_docs' => url('/api-docs.html'),
        'api_base' => url('/api')
    ], 404);
});

Route::get('/san-pham', function () {
    return view('sanpham.index'); // placeholder, lát mình tạo view sau
});

Route::get('/', function () {
    return redirect('/login.html');
});

