<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Получить список всех водителей для API
     */
    public function index()
    {
        // Получаем всех водителей из базы данных
        $drivers = Driver::where('is_confirmed', true)
                   ->where('survey_status', 'approved')
                   ->where('status', '!=', 'blocked')
                   ->select([
                       'id', 
                       'full_name as name',
                       'phone',
                       'car_brand',
                       'car_model',
                       'car_color',
                       'car_year',
                       'license_plate',
                       'tariff as car_class',
                       'status'
                   ])
                   ->orderBy('name')
                   ->get();
                   
        return response()->json($drivers);
    }
    
    /**
     * Получить информацию о конкретном водителе
     */
    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        
        return response()->json([
            'id' => $driver->id,
            'name' => $driver->full_name,
            'phone' => $driver->phone,
            'car_brand' => $driver->car_brand,
            'car_model' => $driver->car_model,
            'car_color' => $driver->car_color,
            'car_year' => $driver->car_year,
            'license_plate' => $driver->license_plate,
            'car_class' => $driver->tariff,
            'status' => $driver->status
        ]);
    }
}
