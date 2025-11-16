<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;

    protected $table = 'vai_tro';
    protected $primaryKey = 'id_vai_tro';
    
    protected $fillable = [
        'ten_vai_tro',
        'mo_ta'
    ];

    // Relationships
    public function taiKhoan()
    {
        return $this->hasMany(TaiKhoan::class, 'id_vai_tro', 'id_vai_tro');
    }
}
