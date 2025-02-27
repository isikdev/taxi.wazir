<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DriverApplicationController extends Controller
{
    /**
     * Отображает список заявок водителей на странице диспетчерской
     */
    public function index()
    {
        // Получаем все заявки водителей со статусом "submitted" (отправлено)
        try {
            $pendingApplications = Driver::where('survey_status', 'submitted')
                ->orderBy('updated_at', 'desc')
                ->get();
            
            // Возвращаем представление со списком заявок
            return view('disp.drivers_control', [
                'pendingApplications' => $pendingApplications
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
        $driver = Driver::with('vehicle')->find($id);
        
        if (!$driver) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }
        
        // Дополнительно проверяем наличие документов и фотографий
        $documentFields = [
            'passport_front', 'passport_back', 'license_front', 'license_back',
            'car_front', 'car_back', 'car_left', 'car_right',
            'interior_front', 'interior_back'
        ];
        
        // Если автомобиль не найден, создаем пустую запись
        if (!$driver->vehicle) {
            // Создаем запись о транспортном средстве
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
            $driver->load('vehicle'); // Перезагружаем с новой связью
        }
        
        // Если есть сессионные данные с путями к документам, добавляем их к водителю
        $documentPaths = session()->get('document_paths', []);
        foreach ($documentFields as $field) {
            if (isset($documentPaths[$field]) && !$driver->$field) {
                $driver->$field = $documentPaths[$field];
            }
        }
        
        return response()->json([
            'driver' => $driver,
            'success' => true
        ]);
    }
    
    /**
     * Одобряет заявку водителя
     */
    public function approveApplication($id)
    {
        $driver = Driver::find($id);
        
        if (!$driver) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }
        
        $driver->update([
            'survey_status' => 'approved',
            'approved_at' => now(),
            'is_confirmed' => true
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Заявка водителя успешно одобрена'
        ]);
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
            // Начинаем транзакцию, чтобы гарантировать целостность данных
            DB::beginTransaction();
            
            // Удаляем связанный автомобиль, если есть
            if ($driver->vehicle) {
                $driver->vehicle->delete();
            }
            
            // Удаляем файлы документов из хранилища
            $documentFields = [
                'passport_front', 'passport_back', 'license_front', 'license_back',
                'car_front', 'car_back', 'car_left', 'car_right',
                'interior_front', 'interior_back'
            ];
            
            foreach ($documentFields as $field) {
                if ($driver->$field) {
                    Storage::disk('public')->delete($driver->$field);
                }
            }
            
            // Удаляем записи водителя из базы данных
            $driver->delete();
            
            // Фиксируем изменения
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Заявка водителя отклонена, и все данные удалены из системы',
                'rejection_reason' => $rejectionReason
            ]);
            
        } catch (\Exception $e) {
            // В случае ошибки отменяем все изменения
            DB::rollBack();
            
            return response()->json([
                'error' => 'Произошла ошибка при удалении данных: ' . $e->getMessage()
            ], 500);
        }
    }
}