<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_color',
        'vehicle_year',
        'vehicle_state_number',
        'chassis_number',
        'sts',
        'stir'
    ];

    /**
     * Связь с моделью Driver (владелец автомобиля)
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
} 