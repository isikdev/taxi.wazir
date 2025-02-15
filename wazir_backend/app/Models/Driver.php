<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $table = 'drivers';
    protected $fillable = [
        'full_name',
        'phone',
        'city',
        'license_number',
        'license_issue_date',
        'license_expiry_date'
    ];
}
