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

        // Создаем тестовые заявки водителей со статусом "pending"
        for ($i = 1; $i <= 5; $i++) {
            Driver::create([
                'full_name' => "Новый Водитель {$i}",
                'phone' => "+99670011{$i}333",
                'date_of_birth' => '1990-01-01',
                'license_number' => "PD{$i}54321",
                'license_issue_date' => '2020-01-01',
                'license_expiry_date' => '2030-01-01',
                'car_brand' => ['Honda', 'Hyundai', 'Kia', 'Mazda', 'Nissan'][$i-1],
                'car_model' => ['Accord', 'Sonata', 'Optima', 'CX-5', 'Altima'][$i-1],
                'car_color' => ['Черный', 'Серый', 'Синий', 'Красный', 'Зеленый'][$i-1],
                'car_year' => 2020 + $i,
                'license_plate' => "C{$i}45DEF",
                'is_confirmed' => false,
                'survey_status' => 'pending',
                'balance' => 0,
                'status' => 'offline',
            ]);
        }

        // Выводим сообщение в консоль
        $this->command->info('Тестовый водитель создан успешно!');
        $this->command->info('Создано 5 тестовых заявок водителей!');
    }
} 