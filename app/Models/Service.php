<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'id_services';

    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'satuan',
        'harga',
        'estimasi_durasi',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'harga' => 'decimal:2',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'service_id', 'id_services');
    }
}
