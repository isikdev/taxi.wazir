<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'driver_id',
        'amount',
        'description',
        'transaction_type',
        'status'
    ];
    
    /**
     * Связь с моделью Driver (водитель)
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    /**
     * Получение форматированной даты создания
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d.m.Y H:i');
    }
}
