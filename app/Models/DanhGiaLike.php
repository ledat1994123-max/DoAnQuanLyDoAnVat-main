<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGiaLike extends Model
{
    use HasFactory;

    protected $table = 'danh_gia_like';
    protected $primaryKey = 'id_like';
    
    protected $fillable = [
        'ma_danh_gia',
        'ma_khach_hang'
    ];

    // Relationships
    public function danhGiaSanPham()
    {
        return $this->belongsTo(DanhGiaSanPham::class, 'ma_danh_gia', 'ma_danh_gia');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }
}
