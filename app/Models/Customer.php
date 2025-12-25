<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    //
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id_customer';

    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'no_wa',
        'alamat',
        'email',
        'password',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id_customer');
    }
}
