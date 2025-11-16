<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGiaSanPham extends Model
{
    use HasFactory;

    protected $table = 'danh_gia_san_pham';
    protected $primaryKey = 'ma_danh_gia';
    
    protected $fillable = [
        'ma_san_pham',
        'ma_khach_hang',
        'so_sao',
        'noi_dung',
        'hinh_anh',
        'da_duyet',
        'ngay_danh_gia'
    ];

    protected $casts = [
        'so_sao' => 'integer',
        'da_duyet' => 'boolean',
        'ngay_danh_gia' => 'datetime'
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function danhGiaLike()
    {
        return $this->hasMany(DanhGiaLike::class, 'ma_danh_gia', 'ma_danh_gia');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('da_duyet', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('so_sao', $rating);
    }

    // Accessors
    public function getSoLuongLikeAttribute()
    {
        return $this->danhGiaLike()->count();
    }
}
