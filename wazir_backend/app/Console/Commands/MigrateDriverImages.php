<?php

namespace App\Console\Commands;

use App\Models\Driver;
use App\Services\DriverImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateDriverImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:migrate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing driver images to the new storage structure';

    /**
     * @var DriverImageService
     */
    protected $imageService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DriverImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting driver images migration...');
        
        // Получаем всех водителей
        $drivers = Driver::all();
        $this->info('Found ' . $drivers->count() . ' drivers to process');
        
        $bar = $this->output->createProgressBar($drivers->count());
        $bar->start();
        
        foreach ($drivers as $driver) {
            $this->migrateDriverImages($driver);
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Driver images migration completed!');
        
        return 0;
    }
    
    /**
     * Миграция изображений для конкретного водителя
     *
     * @param Driver $driver
     * @return void
     */
    protected function migrateDriverImages(Driver $driver)
    {
        // Генерируем персональный номер если его нет
        if (empty($driver->personal_number)) {
            $driver->personal_number = \Illuminate\Support\Str::random(17);
            $driver->save();
        }
        
        $driverIdentifier = $driver->personal_number;
        
        // Массив полей с изображениями
        $imageFields = [
            'passport_front',
            'passport_back',
            'license_front',
            'license_back',
            'car_front',
            'car_back',
            'car_left',
            'car_right',
            'interior_front',
            'interior_back'
        ];
        
        $updatedPaths = [];
        
        foreach ($imageFields as $field) {
            $currentPath = $driver->{$field};
            
            // Пропускаем, если поле пустое или уже обновлено
            if (empty($currentPath)) {
                continue;
            }
            
            // Проверяем, существует ли файл
            if (Storage::disk('public')->exists($currentPath)) {
                // Определяем категорию изображения
                $category = $this->determineCategory($field);
                
                // Получаем имя файла из пути
                $filename = basename($currentPath);
                
                // Формируем новый путь
                $newDirectory = "drivers/{$driverIdentifier}/{$category}";
                $newPath = "{$newDirectory}/{$filename}";
                
                // Создаем директорию если её нет
                Storage::disk('public')->makeDirectory($newDirectory);
                
                // Копируем файл в новое место
                if (Storage::disk('public')->copy($currentPath, $newPath)) {
                    $updatedPaths[$field] = $newPath;
                    
                    // Для отладки
                    $this->output->write('.');
                }
            }
        }
        
        // Обновляем пути в базе данных
        if (!empty($updatedPaths)) {
            $driver->update($updatedPaths);
        }
    }
    
    /**
     * Определяет категорию изображения
     *
     * @param string $imageField Название поля изображения
     * @return string Категория изображения
     */
    protected function determineCategory(string $imageField): string
    {
        $categories = [
            'documents' => ['passport_front', 'passport_back', 'license_front', 'license_back'],
            'car' => ['car_front', 'car_back', 'car_left', 'car_right'],
            'interior' => ['interior_front', 'interior_back']
        ];
        
        foreach ($categories as $category => $fields) {
            if (in_array($imageField, $fields)) {
                return $category;
            }
        }
        
        // По умолчанию, если категория не определена
        return 'misc';
    }
} 