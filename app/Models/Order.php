<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    //
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_orders';

    protected $fillable = [
        'customer_id',
        'user_id',
        'tgl_masuk',
        'tgl_selesai_estimasi',
        'total_harga',
        'status_pembayaran',
        'is_pickup',
    ];

    protected $casts = [
        'tgl_masuk' => 'datetime',
        'tgl_selesai_estimasi' => 'datetime',
        'is_pickup' => 'boolean',
        'total_harga' => 'decimal:2',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer');
    }

    // Relasi ke User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Relasi ke Detail Order (Items)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id_orders');
    }

    // Relasi ke Tracking Status
    public function trackings()
    {
        return $this->hasMany(OrderTracking::class, 'order_id', 'id_orders');
    }

    // Helper: Ambil status terakhir
    public function latestStatus()
    {
        return $this->hasOne(OrderTracking::class, 'order_id', 'id_orders')->latestOfMany();
    }
}
