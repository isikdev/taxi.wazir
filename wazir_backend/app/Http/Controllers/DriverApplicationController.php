<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DriverApplicationController extends Controller
{
    /**
     * Получает общий баланс всех водителей
     * 
     * @return float
     */
    private function getTotalBalance()
    {
        // Используем кеширование для оптимизации
        return Cache::remember('total_balance', 60, function () {
            return Driver::sum('balance');
        });
    }

    /**
     * Отображает список заявок водителей на странице диспетчерской
     */
    public function index()
    {
        try {
            // Получаем все заявки водителей, которые еще не обработаны
            $applications = Driver::where('survey_status', 'pending')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
            
            // Создаем уведомление о новых заявках, если они есть
            if ($applications->count() > 0) {
                app(\App\Http\Controllers\NotificationController::class)->create(
                    'driver_application',
                    'Новые заявки водителей',
                    "Получено {$applications->count()} новых заявок от водителей",
                    ['count' => $applications->count()]
                );
            }
            
            $totalBalance = $this->getTotalBalance();
            
            // Возвращаем представление со списком заявок
            return view('disp.drivers_control', [
                'applications' => $applications,
                'totalBalance' => $totalBalance
            ]);
        } catch (\Exception $e) {
            // Выводим ошибку прямо на экран для отладки
            return '<h1>Ошибка</h1><p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
        }
    }
    
    /**
     * Возвращает данные заявки по ID для отображения в модальном окне
     */
    public function getApplicationDetails($id)
    {
        try {
            $driver = Driver::find($id);
            
            if (!$driver) {
                return response()->json(['error' => 'Заявка не найдена', 'success' => false], 404);
            }
            
            // Оптимизация: загрузить связь с автомобилем только если таблица существует
            if (Schema::hasTable('driver_vehicles')) {
                // Используем жадную загрузку для оптимизации запросов
                $driver->load('vehicle');
            }
            
            return response()->json([
                'driver' => $driver,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Произошла ошибка при загрузке данных: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    
    /**
     * Одобряет заявку водителя
     */
    public function approveApplication($id)
    {
        try {
            $driver = Driver::find($id);
            
            if (!$driver) {
                return response()->json(['error' => 'Заявка не найдена'], 404);
            }
            
            $driver->update([
                'survey_status' => 'approved',
                'approved_at' => now(),
                'is_confirmed' => true
            ]);
            
            // Проверяем существование таблицы driver_vehicles
            if (Schema::hasTable('driver_vehicles')) {
                // Создаем запись о транспортном средстве, если её еще нет
                if (!$driver->vehicle) {
                    $vehicle = new DriverVehicle([
                        'driver_id' => $driver->id,
                        'vehicle_brand' => $driver->car_brand,
                        'vehicle_model' => $driver->car_model,
                        'vehicle_color' => $driver->car_color,
                        'vehicle_year' => $driver->car_year,
                        'vehicle_state_number' => $driver->license_plate,
                        'chassis_number' => $driver->vin,
                        'sts' => $driver->sts,
                        'stir' => $driver->body_number
                    ]);
                    
                    $driver->vehicle()->save($vehicle);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Заявка водителя успешно одобрена'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Произошла ошибка при одобрении заявки: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Отклоняет заявку водителя и удаляет его данные из системы
     */
    public function rejectApplication(Request $request, $id)
    {
        $driver = Driver::find($id);
        
        if (!$driver) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        // Запоминаем информацию о причине отклонения для ответа
        $rejectionReason = $request->input('rejection_reason');
        
        try {
            // Вместо удаления заявки устанавливаем статус "rejected"
            $driver->update([
                'survey_status' => 'rejected',
                'rejection_reason' => $rejectionReason,
                'rejected_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Заявка водителя отклонена',
                'rejection_reason' => $rejectionReason
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Произошла ошибка при отклонении заявки: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Получить детали заявки водителя
     */
    public function show($driverId)
    {
        try {
            $driver = Driver::find($driverId);
            
            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Водитель не найден'
                ]);
            }
            
            // Получаем список столбцов таблицы drivers
            $columns = Schema::getColumnListing('drivers');
            
            // Добавляем отладочную информацию о путях к файлам
            $debug = [];
            
            // Предполагаемые поля изображений для паспорта и прав
            $imageFields = ['passport_front', 'passport_back', 'license_front', 'license_back', 'license_photo'];
            
            // Предполагаемые поля изображений для автомобиля и салона
            $carImageFields = ['car_front', 'car_back', 'car_left', 'car_right', 
                              'car_interior_front', 'car_interior_back'];
            
            // Проверяем наличие полей в таблице и добавляем отладочную информацию
            foreach (array_merge($imageFields, $carImageFields) as $field) {
                // Проверяем, есть ли такое поле в таблице и есть ли в нем значение
                if (in_array($field, $columns) && !empty($driver->$field)) {
                    $path = $driver->$field;
                    $fullPath = storage_path('app/public/' . $path);
                    $debug[$field] = [
                        'db_path' => $path,
                        'full_path' => $fullPath,
                        'exists' => file_exists($fullPath),
                        'url' => asset('storage/' . $path)
                    ];
                } else {
                    // Если поля нет или оно пустое, добавляем информацию об этом
                    $debug[$field] = [
                        'exists' => false,
                        'reason' => !in_array($field, $columns) ? 'Поле отсутствует в БД' : 'Поле пустое'
                    ];
                }
            }
            
            // Добавляем информацию о доступных полях
            $debug['available_columns'] = $columns;
            
            return response()->json([
                'success' => true,
                'driver' => $driver,
                'debug' => $debug
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}