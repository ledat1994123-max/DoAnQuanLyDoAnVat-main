<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'ma_san_pham';
    
    protected $fillable = [
        'ten_san_pham',
        'mo_ta',
        'quy_cach',
        'don_vi',
        'don_gia',
        'ton_kho',
        'url_hinh_anh',
        'ma_danh_muc'
    ];

    protected $casts = [
        'don_gia' => 'decimal:2',
        'ton_kho' => 'integer'
    ];

    // Relationships
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'ma_danh_muc', 'ma_danh_muc');
    }

    public function khuyenMaiSanPham()
    {
        return $this->hasMany(KhuyenMaiSanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function chiTietDonHang()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function danhGiaSanPham()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    // Helper methods
    public function getGiaKhuyenMaiAttribute()
    {
        $khuyenMai = $this->khuyenMaiSanPham()
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->first();
            
        if ($khuyenMai) {
            return $this->don_gia * (1 - $khuyenMai->phan_tram_giam_gia / 100);
        }
        
        return $this->don_gia;
    }
}
