<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiSanPham extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai_san_pham';
    protected $primaryKey = 'ma_khuyen_mai';
    
    protected $fillable = [
        'ma_san_pham',
        'phan_tram_giam_gia',
        'ngay_bat_dau',
        'ngay_ket_thuc'
    ];

    protected $casts = [
        'phan_tram_giam_gia' => 'decimal:2',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('ngay_bat_dau', '<=', now())
                    ->where('ngay_ket_thuc', '>=', now());
    }
}
