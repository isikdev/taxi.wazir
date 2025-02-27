<?php

// app/Http/Controllers/DispatcherController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;


class DispatcherController extends Controller
{
    public function index()
    {
        $totalDrivers = Driver::count();
        $online = Driver::where('status', 'online')->count();
        $free   = Driver::where('status', 'free')->count();
        $busy   = Driver::where('status', 'busy')->count();
        $drivers = Driver::paginate(50);
        
        // Изменяем шаблон с 'disp.index' на 'disp.drivers'
        try {
            return view('disp.drivers', compact('totalDrivers', 'online', 'free', 'busy', 'drivers'));
        } catch (\Exception $e) {
            // Выводим ошибку напрямую для диагностики
            return '<h1>Ошибка при отображении страницы</h1>
                    <p>' . $e->getMessage() . '</p>
                    <pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function list()
    {
        \Log::info('Запрос на получение списка водителей');
        $drivers = Driver::all();
        \Log::info('Количество найденных водителей: ' . $drivers->count());
        
        if ($drivers->count() == 0) {
            // Проверим, почему нет данных, выполнив проверочный запрос к БД
            try {
                $tableName = (new Driver())->getTable();
                $hasTable = Schema::hasTable($tableName);
                \Log::info('Наличие таблицы ' . $tableName . ': ' . ($hasTable ? 'да' : 'нет'));
                
                // Если таблица существует, проверим, есть ли в ней записи напрямую через запрос
                if ($hasTable) {
                    $count = DB::table($tableName)->count();
                    \Log::info('Количество записей в таблице ' . $tableName . ': ' . $count);
                }
                
                // Если в БД нет данных, создадим тестовые данные для отображения
                $testDrivers = [
                    [
                        'id' => 1,
                        'full_name' => 'Тестовый Водитель 1',
                        'phone' => '+996 555 123456',
                        'is_confirmed' => true,
                        'status' => 'free',
                        'license_number' => 'AB123456',
                        'service_type' => 'Эконом'
                    ],
                    [
                        'id' => 2,
                        'full_name' => 'Тестовый Водитель 2',
                        'phone' => '+996 555 654321',
                        'is_confirmed' => false,
                        'status' => 'busy',
                        'license_number' => 'CD789012',
                        'service_type' => 'Комфорт'
                    ]
                ];
                
                \Log::info('Возвращаем тестовые данные для отображения');
                return response()->json($testDrivers);
            } catch (\Exception $e) {
                \Log::error('Ошибка при проверке таблицы: ' . $e->getMessage());
            }
        }
        
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
    
    /**
     * Обработка пополнения баланса водителя
     */
    public function process_balance_payment(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'amount' => 'required|numeric|min:1',
        ]);
        
        $driver = Driver::findOrFail($request->driver_id);
        $amount = (float)$request->amount;
        
        // Пополняем баланс водителя
        $driver->balance += $amount;
        $driver->save();
        
        // Создаем запись о транзакции
        Transaction::create([
            'driver_id' => $driver->id,
            'amount' => $amount,
            'description' => 'Пополнение баланса диспетчером',
            'transaction_type' => 'deposit',
            'status' => 'completed'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Баланс успешно пополнен',
            'new_balance' => $driver->balance
        ]);
    }
    
    public function cars()
    {
        $drivers = Driver::all();
        return view('disp.cars', compact('drivers'));
    }

    public function getDriversList(Request $request)
    {
        try {
            $drivers = Driver::query()
                ->select([
                    'id', 'full_name', 'phone', 'is_confirmed', 
                    'status', 'license_number', 'service_type',
                    'callsign'
                ])
                ->get();

            return response()->json([
                'data' => $drivers,
                'total' => $drivers->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Ошибка при получении списка водителей', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCarsList(Request $request)
    {
        try {
            // Получаем только машины водителей с одобренными заявками
            $cars = Driver::query()
                ->select([
                    'id', 'car_brand', 'car_model', 'car_color', 'car_year', 
                    'license_plate', 'vin', 'body_number', 'sts',
                    'transmission', 'boosters', 'child_seat', 'parking_car',
                    'status'
                ])
                ->whereNotNull('car_brand')
                ->where('survey_status', 'approved')  // Только одобренные заявки
                ->where('is_confirmed', true)         // Только подтвержденные водители
                ->get();

            return response()->json($cars);

        } catch (\Exception $e) {
            \Log::error('Ошибка при получении списка машин', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Получение списка чатов
     */
    public function getChatList()
    {
        try {
            // Временное решение - создаем демо-данные
            // В реальном приложении здесь должен быть запрос к БД
            $chats = [
                [
                    'id' => 1,
                    'name' => 'Александр Иванов',
                    'avatar' => null,
                    'status' => 'Онлайн',
                    'last_message' => 'Здравствуйте, могу я получить заказ?',
                    'unread_count' => 2
                ],
                [
                    'id' => 2,
                    'name' => 'Мария Петрова',
                    'avatar' => null,
                    'status' => 'Офлайн',
                    'last_message' => 'Спасибо за информацию',
                    'unread_count' => 0
                ],
                [
                    'id' => 3,
                    'name' => 'Иван Сидоров',
                    'avatar' => null,
                    'status' => 'Онлайн',
                    'last_message' => 'Когда будет свободная машина?',
                    'unread_count' => 1
                ]
            ];
            
            return response()->json($chats);
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении списка чатов', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Получение сообщений для выбранного чата
     */
    public function getChatMessages($chatId)
    {
        try {
            // Проверяем существование чата
            // В реальном приложении здесь должна быть проверка доступа к чату
            
            // Временное решение - создаем демо-данные
            // В реальном приложении здесь должен быть запрос к БД
            $messages = [];
            
            // Получаем данные пользователя с проверкой на null
            $userId = auth()->check() ? auth()->id() : 0;
            $userName = auth()->check() ? auth()->user()->name : 'Диспетчер';
            
            if ($chatId == 1) {
                $messages = [
                    [
                        'id' => 1,
                        'text' => 'Здравствуйте, могу я получить заказ?',
                        'sender_id' => 101, // ID отправителя (не текущий пользователь)
                        'sender_name' => 'Александр Иванов',
                        'sender_avatar' => null,
                        'is_sender' => false,
                        'created_at' => now()->subHours(3)->toISOString()
                    ],
                    [
                        'id' => 2,
                        'text' => 'Добрый день! Какой район вас интересует?',
                        'sender_id' => $userId,
                        'sender_name' => $userName,
                        'sender_avatar' => null,
                        'is_sender' => true,
                        'created_at' => now()->subHours(2)->toISOString()
                    ],
                    [
                        'id' => 3,
                        'text' => 'Центральный район, улица Ленина',
                        'sender_id' => 101,
                        'sender_name' => 'Александр Иванов',
                        'sender_avatar' => null,
                        'is_sender' => false,
                        'created_at' => now()->subHour()->toISOString()
                    ]
                ];
            } elseif ($chatId == 2) {
                $messages = [
                    [
                        'id' => 4,
                        'text' => 'Здравствуйте, подскажите стоимость поездки до аэропорта',
                        'sender_id' => 102,
                        'sender_name' => 'Мария Петрова',
                        'sender_avatar' => null,
                        'is_sender' => false,
                        'created_at' => now()->subDays(1)->toISOString()
                    ],
                    [
                        'id' => 5,
                        'text' => 'Добрый день! Стоимость поездки составит примерно 500-600 рублей в зависимости от времени в пути',
                        'sender_id' => $userId,
                        'sender_name' => $userName,
                        'sender_avatar' => null,
                        'is_sender' => true,
                        'created_at' => now()->subDays(1)->addHours(1)->toISOString()
                    ],
                    [
                        'id' => 6,
                        'text' => 'Спасибо за информацию',
                        'sender_id' => 102,
                        'sender_name' => 'Мария Петрова',
                        'sender_avatar' => null,
                        'is_sender' => false,
                        'created_at' => now()->subDays(1)->addHours(2)->toISOString()
                    ]
                ];
            } elseif ($chatId == 3) {
                $messages = [
                    [
                        'id' => 7,
                        'text' => 'Здравствуйте, когда будет свободная машина?',
                        'sender_id' => 103,
                        'sender_name' => 'Иван Сидоров',
                        'sender_avatar' => null,
                        'is_sender' => false,
                        'created_at' => now()->subHours(5)->toISOString()
                    ]
                ];
            }
            
            return response()->json($messages);
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении сообщений чата', ['error' => $e->getMessage(), 'chat_id' => $chatId]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Отправка сообщения в чат
     */
    public function sendChatMessage(Request $request, $chatId)
    {
        try {
            // Валидация входных данных
            $request->validate([
                'text' => 'required|string|max:1000',
            ]);
            
            // В реальном приложении здесь должно быть сохранение сообщения в БД
            
            // Возвращаем ID нового сообщения (в реальном приложении это будет ID из БД)
            return response()->json([
                'success' => true,
                'id' => time(), // Используем текущее время как уникальный ID
                'sender_name' => auth()->check() ? auth()->user()->name : 'Диспетчер'
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при отправке сообщения', ['error' => $e->getMessage(), 'chat_id' => $chatId]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Отметка сообщений чата как прочитанных
     */
    public function markChatAsRead(Request $request, $chatId)
    {
        try {
            // В реальном приложении здесь должна быть отметка сообщений как прочитанных в БД
            
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при отметке сообщений как прочитанных', ['error' => $e->getMessage(), 'chat_id' => $chatId]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Отображает список заявок водителей
     */
    public function driverApplications()
    {
        // Получаем список заявок водителей со статусом "на рассмотрении"
        $pendingApplications = \App\Models\Driver::where('survey_status', 'submitted')->orderBy('updated_at', 'desc')->get();
        
        // Получаем списки одобренных и отклоненных заявок
        $approvedApplications = \App\Models\Driver::where('survey_status', 'approved')->orderBy('approved_at', 'desc')->get();
        $rejectedApplications = \App\Models\Driver::where('survey_status', 'rejected')->orderBy('updated_at', 'desc')->get();
        
        return view('disp.driver_applications', [
            'pendingApplications' => $pendingApplications,
            'approvedApplications' => $approvedApplications,
            'rejectedApplications' => $rejectedApplications
        ]);
    }

    /**
     * Одобряет заявку водителя
     */
    public function approveApplication($driverId)
    {
        $driver = \App\Models\Driver::find($driverId);
        
        if (!$driver) {
            return redirect()->back()->with('error', 'Водитель не найден');
        }
        
        $driver->update([
            'survey_status' => 'approved',
            'approved_at' => now(),
            'is_confirmed' => true
        ]);
        
        return redirect()->back()->with('success', 'Заявка водителя успешно одобрена');
    }

    /**
     * Отклоняет заявку водителя
     */
    public function rejectApplication(Request $request, $driverId)
    {
        $driver = \App\Models\Driver::find($driverId);
        
        if (!$driver) {
            return redirect()->back()->with('error', 'Водитель не найден');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $driver->update([
            'survey_status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason')
        ]);
        
        return redirect()->back()->with('success', 'Заявка водителя отклонена');
    }
}