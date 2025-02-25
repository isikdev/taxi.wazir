<?php

namespace App\Http\Controllers\Disp;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Car;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Получение данных о заказах
        $confirmed = Order::where('status', 'confirmed')->count();
        $unconfirmed = Order::where('status', '!=', 'confirmed')->count();
        $total = $confirmed + $unconfirmed;
        
        // Получение данных о зарегистрированных автомобилях
        $active_cars = Car::where('is_active', true)->count();
        $inactive_cars = Car::where('is_active', false)->count();
        $total_cars = $active_cars + $inactive_cars;
        $cars_percentage = ($total_cars > 0) ? round(($active_cars / $total_cars) * 100) : 0;
        
        return view('disp.analytics', compact(
            'confirmed', 
            'unconfirmed', 
            'total',
            'active_cars',
            'inactive_cars',
            'total_cars',
            'cars_percentage'
        ));
    }
} 