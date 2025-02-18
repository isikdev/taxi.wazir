<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'date_of_birth',
        'license_number',
        'license_issue_date',
        'license_expiry_date',
        'personal_number',
        'passport_front',
        'passport_back',
        'license_front',
        'license_back',

        'car_brand',
        'car_model',
        'car_color',
        'car_year',
        'license_plate',
        'has_nakleyka',
        'has_lightbox',
        'has_child_seat',

        'service_type',
        'category',
        'tariff',

        'vin',
        'body_number',
        'sts',
        'callsign',
        'transmission',
        'boosters',
        'child_seat',  
        'parking_car',
        'tariff_extra',
        'is_confirmed'
    ];

    public function isConfirmed()
    {
        return $this->is_confirmed;
    }
}