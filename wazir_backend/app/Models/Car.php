<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'color',
        'year',
        'license_plate',
        'is_active'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
} 