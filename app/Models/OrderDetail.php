<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    //
    use HasFactory;

    protected $table = 'order_details';
    protected $primaryKey = 'id_order_details';

    protected $fillable = [
        'order_id',
        'service_id',
        'qty',
        'harga_saat_ini',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_services');
    }
}
