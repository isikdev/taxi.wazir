<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'driver_id',
        'pickup_address',
        'destination_address',
        'status',  // confirmed, pending, canceled, etc.
        'price',
        'created_at',
        'updated_at'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
} 