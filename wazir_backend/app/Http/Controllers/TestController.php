<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestController extends Controller
{
    public function testTables()
    {
        $result = [
            'drivers_exists' => Schema::hasTable('drivers'),
            'transactions_exists' => Schema::hasTable('transactions'),
            'tables' => DB::select('SHOW TABLES')
        ];
        
        return response()->json($result);
    }
} 