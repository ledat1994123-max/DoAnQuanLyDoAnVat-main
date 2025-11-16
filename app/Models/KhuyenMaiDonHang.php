<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiDonHang extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai_don_hang';
    protected $primaryKey = 'id_km_don_hang';
    
    protected $fillable = [
        'code',
        'so_tien_giam',
        'han_su_dung',
        'trang_thai'
    ];

    protected $casts = [
        'so_tien_giam' => 'decimal:2',
        'han_su_dung' => 'datetime',
        'trang_thai' => 'boolean'
    ];

    // Relationships
    public function donHang()
    {
        return $this->hasMany(DonHang::class, 'id_km_don_hang_fk', 'id_km_don_hang');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1)
                    ->where('han_su_dung', '>=', now());
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
