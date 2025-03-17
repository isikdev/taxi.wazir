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
        'license_photo',
        
        // Фотографии автомобиля
        'car_front',
        'car_back',
        'car_left',
        'car_right',
        'car_interior_front',
        'car_interior_back',

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
        'is_confirmed',
        
        // Новые поля для отслеживания статуса анкеты
        'survey_status',
        'rejection_reason',
        'approved_at',
        
        // Поле для баланса водителя
        'balance',
        'status',
        
        // Координаты для карты
        'lat',
        'lng'
    ];

    // При создании водителя устанавливаем значение по умолчанию для баланса
    protected $attributes = [
        'balance' => 0,
        'status' => 'offline',
        'survey_status' => '',
        'is_confirmed' => false
    ];

    /**
     * Мутатор для атрибута phone
     * Обеспечивает форматирование телефонного номера с кодом страны +996
     */
    public function setPhoneAttribute($value)
    {
        // Нормализуем номер телефона, удаляем все нецифровые символы кроме плюса
        $phone = preg_replace('/[^0-9+]/', '', $value);
        
        // Добавляем киргизский код страны если его нет
        if (!str_starts_with($phone, '+996')) {
            // Если номер начинается с "0", убираем его
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            
            // Если начинается с "996", добавляем "+"
            if (str_starts_with($phone, '996')) {
                $phone = '+' . $phone;
            } 
            // Если нет кода, но есть остальные цифры, добавляем код +996
            else if (!empty($phone) && !str_starts_with($phone, '+')) {
                $phone = '+996' . $phone;
            }
        }
        
        $this->attributes['phone'] = $phone;
    }

    /**
     * Связь с моделью Vehicle (автомобиль водителя)
     */
    public function vehicle()
    {
        return $this->hasOne(DriverVehicle::class);
    }

    /**
     * Связь с моделью Transaction (история транзакций)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function isConfirmed()
    {
        return $this->is_confirmed;
    }
}