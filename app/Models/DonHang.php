<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';
    protected $primaryKey = 'ma_don_hang';
    
    protected $fillable = [
        'ma_khach_hang',
        'ngay_lap',
        'id_km_don_hang_fk',
        'tong_tien',
        'dia_chi_giao_hang',
        'phuong_thuc_thanh_toan',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_lap' => 'datetime',
        'tong_tien' => 'decimal:2'
    ];

    // Relationships
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function khuyenMaiDonHang()
    {
        return $this->belongsTo(KhuyenMaiDonHang::class, 'id_km_don_hang_fk', 'id_km_don_hang');
    }

    public function chiTietDonHang()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('trang_thai', $status);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('ma_khach_hang', $customerId);
    }
}
