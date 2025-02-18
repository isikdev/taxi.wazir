<?php

// app/Http/Controllers/DispatcherController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Cache;


class DispatcherController extends Controller
{
    public function index()
    {
        $totalDrivers = Driver::count();
        $online = Driver::where('status', 'online')->count();
        $free   = Driver::where('status', 'free')->count();
        $busy   = Driver::where('status', 'busy')->count();
        $drivers = Driver::paginate(50);
        return view('disp.index', compact('totalDrivers', 'online', 'free', 'busy', 'drivers'));
    }

    public function list()
    {
        $drivers = Driver::all();
        return response()->json($drivers);
    }    

    public function chat()
    {
        $users = Driver::all();
        return view('disp.chat', compact('users'));
    }
    public function analytics()
    {
        $total = Driver::count();
        $confirmed = Driver::where('is_confirmed', true)->count();
        $unconfirmed = $total - $confirmed;
        return view('disp.analytics', compact('total', 'confirmed', 'unconfirmed'));
    }
    public function pay_balance()
    {
        $drivers = Driver::all();
        return view('disp.pay_balance', compact('drivers'));
    }
    public function cars()
    {
        $drivers = Driver::all();
        return view('disp.cars', compact('drivers'));
    }
}