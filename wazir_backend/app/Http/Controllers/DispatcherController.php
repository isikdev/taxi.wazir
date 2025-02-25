<?php

// app/Http/Controllers/DispatcherController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Cache;


class DispatcherController extends Controller
{
    public function index()
    {
        $totalDrivers = Driver::count();
        $online = Driver::where('status', 'online')->count();
        $free   = Driver::where('status', 'free')->count();
        $busy   = Driver::where('status', 'busy')->count();
        $drivers = Driver::paginate(50);
        return view('disp.index', compact('totalDrivers', 'online', 'free', 'busy', 'drivers'));
    }

    public function list()
    {
        $drivers = Driver::all();
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
            // Здесь мы получаем данные о машинах из таблицы drivers
            // поскольку, судя по структуре БД, машины хранятся там
            $cars = Driver::query()
                ->select([
                    'id', 'car_brand', 'car_model', 'car_color', 'car_year', 
                    'license_plate', 'vin', 'body_number', 'sts',
                    'status'
                ])
                ->whereNotNull('car_brand')
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
}