<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'khach_hang';
    protected $primaryKey = 'ma_khach_hang';
    
    protected $fillable = [
        'id_tai_khoan',
        'ten_khach_hang',
        'so_dien_thoai',
        'email',
        'dia_chi'
    ];

    // Relationships
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'id_tai_khoan', 'id_tai_khoan');
    }

    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function danhGiaSanPham()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'ma_khach_hang', 'ma_khach_hang');
    }

    public function danhGiaLike()
    {
        return $this->hasMany(DanhGiaLike::class, 'ma_khach_hang', 'ma_khach_hang');
    }
}
