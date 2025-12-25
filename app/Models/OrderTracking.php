<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderTracking extends Model
{
    //
    use HasFactory;

    protected $table = 'order_tracking';
    protected $primaryKey = 'id_order_tracking';

    protected $fillable = [
        'order_id',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
}
