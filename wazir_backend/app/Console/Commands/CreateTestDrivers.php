<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Driver;

class CreateTestDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создает тестовых водителей в базе данных для отладки';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Создание тестовых водителей...');
        
        $testDrivers = [
            [
                'full_name' => 'Тестовый Водитель 1',
                'phone' => '+996 555 123456',
                'date_of_birth' => '1990-01-01',
                'license_number' => 'AB123456',
                'license_issue_date' => '2020-01-01',
                'license_expiry_date' => '2030-01-01',
                'is_confirmed' => true,
                'status' => 'free',
                'service_type' => 'Эконом',
                'callsign' => 'T1',
                'car_brand' => 'Toyota',
                'car_model' => 'Camry',
                'car_color' => 'Белый',
                'car_year' => '2020',
                'license_plate' => 'KG 123 AB',
            ],
            [
                'full_name' => 'Тестовый Водитель 2',
                'phone' => '+996 555 654321',
                'date_of_birth' => '1985-05-15',
                'license_number' => 'CD789012',
                'license_issue_date' => '2019-06-01',
                'license_expiry_date' => '2029-06-01',
                'is_confirmed' => false,
                'status' => 'busy',
                'service_type' => 'Комфорт',
                'callsign' => 'T2',
                'car_brand' => 'Mercedes',
                'car_model' => 'E-Class',
                'car_color' => 'Черный',
                'car_year' => '2021',
                'license_plate' => 'KG 456 CD',
            ],
            [
                'full_name' => 'Тестовый Водитель 3',
                'phone' => '+996 555 987654',
                'date_of_birth' => '1992-10-20',
                'license_number' => 'EF345678',
                'license_issue_date' => '2021-03-15',
                'license_expiry_date' => '2031-03-15',
                'is_confirmed' => true,
                'status' => 'online',
                'service_type' => 'Бизнес',
                'callsign' => 'T3',
                'car_brand' => 'BMW',
                'car_model' => '5-Series',
                'car_color' => 'Синий',
                'car_year' => '2022',
                'license_plate' => 'KG 789 EF',
            ]
        ];
        
        $count = 0;
        foreach ($testDrivers as $driverData) {
            // Проверим, существует ли уже водитель с таким телефоном
            $existingDriver = Driver::where('phone', $driverData['phone'])->first();
            
            if (!$existingDriver) {
                Driver::create($driverData);
                $count++;
                $this->info("Создан водитель: {$driverData['full_name']}");
            } else {
                $this->warn("Водитель с телефоном {$driverData['phone']} уже существует");
            }
        }
        
        $this->info("Создано {$count} тестовых водителей");
        return 0;
    }
}
