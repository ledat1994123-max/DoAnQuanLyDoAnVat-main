<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TaiKhoan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tai_khoan';
    protected $primaryKey = 'id_tai_khoan';
    
    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'id_vai_tro',
        'ngay_tao'
    ];

    protected $casts = [
        'ngay_tao' => 'datetime'
    ];


    // Relationships
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'id_vai_tro', 'id_vai_tro');
    }

    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'id_tai_khoan', 'id_tai_khoan');
    }

    public function quanTriVien()
    {
        return $this->hasOne(QuanTriVien::class, 'id_tai_khoan', 'id_tai_khoan');
    }
}
