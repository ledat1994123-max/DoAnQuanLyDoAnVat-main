<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TinTuc extends Model
{
    use HasFactory;

    protected $table = 'tin_tuc';
    protected $primaryKey = 'ma_tin';
    
    protected $fillable = [
        'tieu_de',
        'tom_tat',
        'noi_dung',
        'url_hinh_anh',
        'ngay_dang',
        'ma_quan_tri_vien'
    ];

    protected $casts = [
        'ngay_dang' => 'datetime'
    ];

    // Relationships
    public function quanTriVien()
    {
        return $this->belongsTo(QuanTriVien::class, 'ma_quan_tri_vien', 'ma_quan_tri_vien');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('ngay_dang', '<=', now());
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('ngay_dang', 'desc');
    }
}
