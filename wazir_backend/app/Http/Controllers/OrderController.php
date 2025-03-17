<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Возвращает страницу создания нового заказа
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Генерируем номер заказа
        $orderNumber = Order::generateOrderNumber();
        
        // Текущая дата и время
        $currentDate = now()->format('d.m.y');
        $currentTime = now()->format('H:i');
        
        return view('disp.new_order', compact('orderNumber', 'currentDate', 'currentTime'));
    }
    
    /**
     * Сохраняет новый заказ
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Валидация данных
            $validator = Validator::make($request->all(), [
                'order_number' => 'required|string',
                'tariff' => 'required|string',
                'payment_method' => 'required|string',
                'origin_street' => 'required|string',
                'destination_street' => 'required|string',
                'price' => 'required',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка валидации данных',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Преобразование даты и времени в нужный формат
            $date = null;
            $time = null;
            
            if ($request->has('date')) {
                try {
                    $date = \Carbon\Carbon::createFromFormat('d.m.y', $request->date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $date = now()->format('Y-m-d');
                }
            }
            
            if ($request->has('time')) {
                try {
                    $time = \Carbon\Carbon::createFromFormat('H:i', $request->time)->format('H:i:s');
                } catch (\Exception $e) {
                    $time = now()->format('H:i:s');
                }
            }
            
            // Создание нового заказа
            $order = Order::create([
                'order_number' => $request->order_number,
                'date' => $date,
                'time' => $time,
                'waybill' => $request->waybill,
                'phone' => $request->phone,
                'client_name' => $request->client_name,
                'tariff' => $request->tariff,
                'payment_method' => $request->payment_method,
                'origin_street' => $request->origin_street,
                'origin_house' => $request->origin_house, 
                'origin_district' => $request->origin_district,
                'destination_street' => $request->destination_street,
                'destination_house' => $request->destination_house,
                'destination_district' => $request->destination_district,
                'distance' => $request->distance,
                'duration' => $request->duration,
                'price' => $request->price,
                'notes' => $request->notes,
                'status' => 'new'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно создан',
                'order' => $order
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Ошибка при создании заказа: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при создании заказа',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получает список заказов
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $orders = Order::query();
            
            // Фильтрация по статусу
            if ($request->has('status') && $request->status) {
                $orders->where('status', $request->status);
            }
            
            // Поиск по номеру заказа или телефону
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $orders->where(function($query) use ($search) {
                    $query->where('order_number', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%")
                          ->orWhere('client_name', 'like', "%{$search}%");
                });
            }
            
            // Добавляем информацию о водителе для каждого заказа
            $orders->with('driver');
            
            // Сортировка по дате (сначала новые)
            $orders->orderBy('created_at', 'desc');
            
            // Пагинация
            $perPage = $request->get('per_page', 20);
            $result = $orders->paginate($perPage);
            
            // Если заказов нет, создаем тестовые данные для демонстрации
            if ($result->isEmpty()) {
                // Проверяем существование таблицы
                $hasTable = Schema::hasTable('orders');
                
                if ($hasTable) {
                    // Генерируем тестовые заказы
                    $testOrders = $this->generateTestOrders();
                    
                    // Обновляем объект пагинации тестовыми данными
                    $result = new \Illuminate\Pagination\LengthAwarePaginator(
                        $testOrders,
                        count($testOrders),
                        $perPage,
                        1,
                        ['path' => url('/api/orders')]
                    );
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ошибка при получении списка заказов: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при получении списка заказов',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получает детали заказа
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ошибка при получении деталей заказа: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при получении деталей заказа',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Обновляет статус заказа
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:new,in_progress,completed,cancelled',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некорректный статус',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Статус заказа успешно обновлен',
                'data' => $order
            ]);
            
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении статуса заказа: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при обновлении статуса заказа',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Генерирует тестовые данные заказов для демонстрации
     *
     * @return array
     */
    private function generateTestOrders()
    {
        $statuses = ['new', 'in_progress', 'completed', 'cancelled'];
        $streets = ['ул. Киевская', 'ул. Манаса', 'ул. Чуй', 'ул. Токтогула', 'ул. Боконбаева', 'ул. Московская'];
        $districts = ['Центр', 'Аламедин', 'Джал', 'Восток-5', 'Тунгуч', 'Юг-2'];
        $testOrders = [];
        
        // Получаем всех водителей
        $drivers = \App\Models\Driver::all();
        
        // Создаем 10 тестовых заказов
        for ($i = 1; $i <= 10; $i++) {
            $status = $statuses[array_rand($statuses)];
            $originStreet = $streets[array_rand($streets)];
            $destStreet = $streets[array_rand($streets)];
            $driver = $drivers->isNotEmpty() ? $drivers->random() : null;
            
            $testOrder = new \stdClass();
            $testOrder->id = $i;
            $testOrder->order_number = 'ORD' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT);
            $testOrder->date = date('Y-m-d');
            $testOrder->time = date('H:i:s');
            $testOrder->client_name = 'Тестовый Клиент ' . $i;
            $testOrder->phone = '+996 ' . rand(500, 799) . ' ' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $testOrder->tariff = rand(0, 1) ? 'Стандарт' : 'Комфорт';
            $testOrder->payment_method = rand(0, 1) ? 'Наличные' : 'Карта';
            $testOrder->origin_street = $originStreet;
            $testOrder->origin_house = rand(1, 100);
            $testOrder->origin_district = $districts[array_rand($districts)];
            $testOrder->destination_street = $destStreet;
            $testOrder->destination_house = rand(1, 100);
            $testOrder->destination_district = $districts[array_rand($districts)];
            $testOrder->distance = rand(1, 20) . '.' . rand(0, 9) . ' км';
            $testOrder->duration = rand(5, 40) . ' мин';
            $testOrder->price = rand(100, 1000);
            $testOrder->status = $status;
            $testOrder->created_at = date('Y-m-d H:i:s');
            $testOrder->updated_at = date('Y-m-d H:i:s');
            
            // Добавляем информацию о водителе, если заказ не новый
            if ($status !== 'new' && $driver) {
                $testOrder->driver_id = $driver->id;
                $testOrder->driver = $driver;
            }
            
            $testOrders[] = $testOrder;
        }
        
        return $testOrders;
    }
}
