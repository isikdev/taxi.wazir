<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DispatcherController extends Controller
{
    public function index()
    {
        $totalDrivers = Driver::count();
        $onLine = 0;
        $free = 0;
        $busy = 0;
        $drivers = Driver::all();
        return view('disp.drivers', compact('totalDrivers', 'onLine', 'free', 'busy', 'drivers'));
    }

    public function list()
    {
        $drivers = Driver::all();
        return response()->json($drivers);
    }
}
