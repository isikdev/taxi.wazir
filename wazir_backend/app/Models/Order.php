<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'date',
        'time',
        'waybill',
        'phone',
        'client_name',
        'tariff',
        'payment_method',
        'origin_street',
        'origin_house',
        'origin_district',
        'destination_street',
        'destination_house',
        'destination_district',
        'distance',
        'duration',
        'price',
        'notes',
        'status',
        'driver_id',
        'client_id'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Получает статусы заказа в виде массива для селекта
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            'new' => 'Новый',
            'in_progress' => 'В процессе',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен',
        ];
    }

    /**
     * Отношение к водителю
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Отношение к клиенту
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Генерирует уникальный номер заказа
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = rand(1000, 9999);
        
        return $prefix . $timestamp . $random;
    }
} 