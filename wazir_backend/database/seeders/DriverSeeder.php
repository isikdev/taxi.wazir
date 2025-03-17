<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use Carbon\Carbon;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем тестового водителя
        Driver::create([
            'full_name' => 'Тестовый Водитель',
            'phone' => '+996700111222',
            'license_number' => 'AB123456',
            'license_issue_date' => '2015-01-01',
            'license_expiry_date' => '2025-01-01',
            'car_brand' => 'Toyota',
            'car_model' => 'Camry',
            'car_color' => 'Белый',
            'car_year' => 2018,
            'license_plate' => 'B123ABC',
            'is_confirmed' => true,
            'survey_status' => 'approved',
            'approved_at' => Carbon::now(),
            'balance' => 10000,
            'status' => 'online',
        ]);

        // Выводим сообщение в консоль
        $this->command->info('Тестовый водитель создан успешно!');
    }
} 