<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuanTriVien extends Model
{
    use HasFactory;

    protected $table = 'quan_tri_vien';
    protected $primaryKey = 'ma_quan_tri_vien';
    
    protected $fillable = [
        'id_tai_khoan',
        'ten_quan_tri_vien',
        'email'
    ];

    // Relationships
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'id_tai_khoan', 'id_tai_khoan');
    }

    public function tinTuc()
    {
        return $this->hasMany(TinTuc::class, 'ma_quan_tri_vien', 'ma_quan_tri_vien');
    }
}
