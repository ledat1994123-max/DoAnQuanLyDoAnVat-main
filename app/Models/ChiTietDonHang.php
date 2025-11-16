<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_hang';
    protected $primaryKey = 'id_chi_tiet_dh';
    
    protected $fillable = [
        'ma_don_hang',
        'ma_san_pham',
        'so_luong',
        'don_gia_luc_mua'
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'don_gia_luc_mua' => 'decimal:2'
    ];

    // Relationships
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    // Accessors
    public function getThanhTienAttribute()
    {
        return $this->so_luong * $this->don_gia_luc_mua;
    }
}
