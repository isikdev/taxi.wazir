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
    /**
     * Получает общий баланс всех водителей
     * 
     * @return float
     */
    private function getTotalBalance()
    {
        // Используем кэширование на 24 часа для значительного ускорения
        return Cache::remember('total_balance', 86400, function () {
            return Driver::sum('balance');
        });
    }
    
    /**
     * Обновляет общий баланс в кэше после изменений
     */
    private function updateTotalBalanceCache()
    {
        $totalBalance = Driver::sum('balance');
        Cache::put('total_balance', $totalBalance, 86400);
        return $totalBalance;
    }

    public function index()
    {
        $totalDrivers = Driver::count();
        $online = Driver::where('status', 'online')->count();
        $free   = Driver::where('status', 'free')->count();
        $busy   = Driver::where('status', 'busy')->count();
        $drivers = Driver::paginate(50);
        $totalBalance = $this->getTotalBalance();
        
        // Изменяем шаблон с 'disp.index' на 'disp.drivers'
        try {
            return view('disp.index', compact('totalDrivers', 'online', 'free', 'busy', 'drivers', 'totalBalance'));
        } catch (\Exception $e) {
            // Выводим ошибку напрямую для диагностики
            return '<h1>Ошибка при отображении страницы</h1>
                    <p>' . $e->getMessage() . '</p>
                    <pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    public function list(Request $request)
    {
        \Log::info('Запрос на получение списка водителей с фильтрами');
        
        // Проверяем, запрос только на счетчики
        if ($request->has('count_only') && $request->input('count_only')) {
            $counts = [
                'online' => Driver::where('status', 'online')->count(),
                'free' => Driver::where('status', 'free')->count(),
                'busy' => Driver::where('status', 'busy')->count(),
                'total' => Driver::count()
            ];
            
            return response()->json(['counts' => $counts]);
        }
        
        // Получаем параметры фильтрации
        $status = $request->input('status');
        $stateFilter = $request->input('state');
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        
        // Начинаем формировать запрос
        $query = Driver::query();
        
        // Применяем фильтры, если они указаны
        if ($status) {
            if ($status === 'confirmed') {
                $query->where('is_confirmed', true);
            } elseif ($status === 'unconfirmed') {
                $query->where('is_confirmed', false);
            }
        }
        
        if ($stateFilter) {
            $query->where('status', $stateFilter);
        }
        
        // Применяем поиск, если указан
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('callsign', 'like', "%{$search}%");
            });
        }
        
        // Получаем общее количество записей после применения фильтров
        $total = $query->count();
        
        // Применяем пагинацию
        $drivers = $query->skip(($page - 1) * $perPage)
                         ->take($perPage)
                         ->get();
        
        \Log::info('Количество найденных водителей: ' . $drivers->count() . ' из ' . $total);
        
        // Показываем тестовые данные только если нет фильтров и база пуста
        if ($drivers->count() == 0 && $total == 0 && !$status && !$stateFilter && !$search) {
            // Проверка, если нет данных, как в оригинальном методе
            try {
                $tableName = (new Driver())->getTable();
                $hasTable = Schema::hasTable($tableName);
                \Log::info('Наличие таблицы ' . $tableName . ': ' . ($hasTable ? 'да' : 'нет'));
                
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
                return response()->json([
                    'data' => $testDrivers,
                    'total' => count($testDrivers),
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage
                ]);
            } catch (\Exception $e) {
                \Log::error('Ошибка при проверке таблицы: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'data' => $drivers,
            'total' => $total,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage
        ]);
    }    

    public function chat()
    {
        $totalBalance = $this->getTotalBalance();
        $users = Driver::all();
        return view('disp.chat', compact('totalBalance', 'users'));
    }
    public function analytics(Request $request)
    {
        $totalBalance = $this->getTotalBalance();
        // Обработка фильтров
        $datePreset = $request->input('date_preset');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'all');
        
        // Для отладки
        \Log::info('Получены фильтры', [
            'date_preset' => $datePreset,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status
        ]);
        
        // Подготавливаем запрос для водителей с учетом фильтров
        $driversQuery = Driver::query();
        
        // Применяем фильтр по дате, если указан
        if ($startDate && $endDate) {
            $driversQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($datePreset) {
            // Если указан предустановленный период
            $today = now()->format('Y-m-d');
            
            switch ($datePreset) {
                case 'today':
                    $driversQuery->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $driversQuery->whereDate('created_at', now()->subDay()->format('Y-m-d'));
                    break;
                case 'week':
                    $driversQuery->whereBetween('created_at', [
                        now()->startOfWeek()->format('Y-m-d') . ' 00:00:00',
                        $today . ' 23:59:59'
                    ]);
                    break;
                case 'month':
                    $driversQuery->whereBetween('created_at', [
                        now()->startOfMonth()->format('Y-m-d') . ' 00:00:00',
                        $today . ' 23:59:59'
                    ]);
                    break;
            }
        }
        
        // Применяем фильтр по статусу, если указан
        if ($status && $status !== 'all') {
            if ($status === 'free') {
                $driversQuery->where('status', 'free');
            } elseif ($status === 'busy') {
                $driversQuery->where('status', 'busy');
            } elseif ($status === 'cancelled') {
                $driversQuery->where('status', 'cancelled');
            }
        }
        
        // Статистика по водителям с применением фильтров
        $total = $driversQuery->count();
        $confirmed = (clone $driversQuery)->where('is_confirmed', true)->count();
        $unconfirmed = $total - $confirmed;
        
        // Вычисляем процент подтвержденных водителей
        $percentage = ($total > 0) ? round(($confirmed / $total) * 100) : 0;
        
        // Подготавливаем запрос для автомобилей с теми же фильтрами
        $carsQuery = Driver::query();
        
        // Применяем те же фильтры по дате к запросу автомобилей
        if ($startDate && $endDate) {
            $carsQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($datePreset) {
            $today = now()->format('Y-m-d');
            
            switch ($datePreset) {
                case 'today':
                    $carsQuery->whereDate('created_at', $today);
                    break;
                case 'yesterday':
                    $carsQuery->whereDate('created_at', now()->subDay()->format('Y-m-d'));
                    break;
                case 'week':
                    $carsQuery->whereBetween('created_at', [
                        now()->startOfWeek()->format('Y-m-d') . ' 00:00:00',
                        $today . ' 23:59:59'
                    ]);
                    break;
                case 'month':
                    $carsQuery->whereBetween('created_at', [
                        now()->startOfMonth()->format('Y-m-d') . ' 00:00:00',
                        $today . ' 23:59:59'
                    ]);
                    break;
            }
        }
        
        // Применяем фильтр по статусу к автомобилям
        if ($status && $status !== 'all') {
            if ($status === 'free') {
                $carsQuery->where('status', 'free');
            } elseif ($status === 'busy') {
                $carsQuery->where('status', 'busy');
            } elseif ($status === 'cancelled') {
                $carsQuery->where('status', 'cancelled');
            }
        }
        
        // Подсчитываем количество авто с указанными фильтрами
        $cars_with_data = (clone $carsQuery)->whereNotNull('car_brand')
                          ->whereNotNull('car_model')
                          ->count();
        
        // Активные автомобили
        $active_cars = (clone $carsQuery)->whereNotNull('car_brand')
                      ->whereNotNull('car_model')
                      ->where('is_confirmed', true)
                      ->where('survey_status', 'approved')
                      ->count();
        
        $inactive_cars = $cars_with_data - $active_cars;
        $total_cars = $cars_with_data;
        $cars_percentage = ($total_cars > 0) ? round(($active_cars / $total_cars) * 100) : 0;
        
        // Получаем данные о транзакциях водителей
        $transactionsData = $this->getTransactionsData($datePreset, $startDate, $endDate);
        $transactions_count = $transactionsData['count'];
        $transactions_sum = $transactionsData['sum'];

        // Переформатируем данные для совместимости с JavaScript
        $transactions_data = [
            'labels' => $transactionsData['labels'],
            'values' => $transactionsData['values'],
            'count' => $transactions_count,
            'sum' => $transactions_sum
        ];
        
        // Если это AJAX запрос, возвращаем данные в JSON формате
        if ($request->ajax()) {
            $responseData = [
                'total' => (int)$total,
                'confirmed' => (int)$confirmed, 
                'unconfirmed' => (int)$unconfirmed,
                'percentage' => (int)$percentage,
                'active_cars' => (int)$active_cars,
                'inactive_cars' => (int)$inactive_cars,
                'total_cars' => (int)$total_cars,
                'cars_percentage' => (int)$cars_percentage,
                'transactions' => [
                    'count' => $transactionsData['count'],
                    'sum' => $transactionsData['sum'],
                    'labels' => $transactionsData['labels'],
                    'values' => $transactionsData['values']
                ],
                // Добавляем информацию о примененных фильтрах для отладки
                'applied_filters' => [
                    'date_preset' => $datePreset,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => $status
                ]
            ];
            
            \Log::info('Отправляем ответ с данными о транзакциях', $responseData);
            
            return response()->json($responseData);
        }
        
        // Иначе возвращаем обычное представление
        return view('disp.analytics', compact(
            'total', 'confirmed', 'unconfirmed',
            'active_cars', 'inactive_cars', 'total_cars', 'cars_percentage',
            'totalBalance', 'transactions_count', 'transactions_sum', 'transactions_data'
        ));
    }
    public function pay_balance()
    {
        // Получаем список всех водителей
        $drivers = Driver::all();
        $totalBalance = $this->getTotalBalance();
        
        // Отображаем страницу пополнения баланса
        return view('disp.pay_balance', compact('drivers', 'totalBalance'));
    }
    
    public function process_balance_payment(Request $request)
    {
        // Режим AJAX запроса
        $isAjax = $request->ajax();
        
        try {
            // Валидация данных
            $validated = $request->validate([
                'driver_id' => 'required|exists:drivers,id',
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'nullable|in:cash,card,transfer',
                'comment' => 'nullable|string|max:255'
            ]);
            
            // Находим водителя
            $driver = Driver::findOrFail($request->driver_id);
            
            // Обновляем баланс
            $oldBalance = $driver->balance ?? 0;
            $driver->balance += $request->amount;
            $driver->save();
            
            // Записываем транзакцию, если таблица существует
            if (Schema::hasTable('transactions')) {
                Transaction::create([
                    'driver_id' => $driver->id,
                    'amount' => $request->amount,
                    'description' => $request->comment ?? 'Пополнение через диспетчера',
                    'transaction_type' => 'deposit',
                    'status' => 'completed'
                ]);
            }
            
            // Обновляем кеш общего баланса
            $this->updateTotalBalanceCache();
            
            // Создаем уведомление о пополнении баланса с новой системой уведомлений
            try {
                // Проверяем существует ли сервис уведомлений
                if (class_exists('\App\Services\NotificationService')) {
                    $notificationService = app('\App\Services\NotificationService');
                    
                    // Дата и время для уведомления
                    $now = \Carbon\Carbon::now()->format('d.m.Y H:i:s');
                    
                    // Создаем уведомление
                    $notificationService->createGlobal(
                        'balance',
                        'Пополнение баланса',
                        [
                            'message' => "Баланс водителя {$driver->full_name} пополнен на {$request->amount} ₸ ({$now})",
                            'driver_id' => $driver->id,
                            'driver_name' => $driver->full_name,
                            'amount' => $request->amount,
                            'old_balance' => $oldBalance,
                            'new_balance' => $driver->balance,
                            'datetime' => $now,
                            'payment_method' => $validated['payment_method'] ?? 'cash'
                        ]
                    );
                }
            } catch (\Exception $e) {
                // Логируем ошибку, но продолжаем выполнение
                \Log::warning('Ошибка при создании уведомления: ' . $e->getMessage());
            }
            
            // Для Ajax запросов возвращаем JSON
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Баланс успешно пополнен',
                    'new_balance' => $driver->balance,
                    'total_balance' => $this->getTotalBalance()
                ]);
            }
            
            // Для обычных запросов возвращаем редирект
            return redirect()->back()->with('success', 'Баланс успешно пополнен');
        } catch (\Exception $e) {
            \Log::error('Ошибка при пополнении баланса: ' . $e->getMessage());
            
            // Для Ajax запросов возвращаем JSON с ошибкой
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при пополнении баланса: ' . $e->getMessage()
                ], 500);
            }
            
            // Для обычных запросов возвращаем редирект с ошибкой
            return redirect()->back()->with('error', 'Ошибка при пополнении баланса: ' . $e->getMessage());
        }
    }
    
    public function cars()
    {
        $totalBalance = $this->getTotalBalance();
        $drivers = Driver::all();
        return view('disp.cars', compact('totalBalance', 'drivers'));
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

    /**
     * Возвращает список водителей в формате JSON
     */
    public function getDriversJson(Request $request)
    {
        // Получаем всех водителей без фильтрации по статусу
        $drivers = Driver::all();
        
        // Проверяем, есть ли водители в базе данных
        if ($drivers->isEmpty()) {
            // Если водителей нет, возвращаем тестовые данные
            return [
                [
                    'id' => 1,
                    'full_name' => 'Тестовый Водитель 1',
                    'phone' => '+996 555 123456',
                    'car_brand' => 'Toyota',
                    'car_model' => 'Camry',
                    'car_color' => 'Черный',
                    'car_year' => '2020',
                    'license_plate' => 'KG 1234 AB',
                    'status' => 'free',
                    'lat' => 42.8746,
                    'lng' => 74.5698
                ],
                [
                    'id' => 2,
                    'full_name' => 'Тестовый Водитель 2',
                    'phone' => '+996 555 654321',
                    'car_brand' => 'Honda',
                    'car_model' => 'Accord',
                    'car_color' => 'Белый',
                    'car_year' => '2021',
                    'license_plate' => 'KG 5678 CD',
                    'status' => 'free',
                    'lat' => 42.8756,
                    'lng' => 74.5708
                ],
                [
                    'id' => 3,
                    'full_name' => 'Тестовый Водитель 3',
                    'phone' => '+996 555 987654',
                    'car_brand' => 'BMW',
                    'car_model' => 'X5',
                    'car_color' => 'Серебристый',
                    'car_year' => '2022',
                    'license_plate' => 'KG 9012 EF',
                    'status' => 'busy',
                    'lat' => 42.8736,
                    'lng' => 74.5688
                ]
            ];
        }
        
        // Для тестирования добавляем случайные координаты в Бишкеке, если они отсутствуют
        $drivers = $drivers->map(function($driver) {
            // Центр Бишкека
            $centerLat = 42.8746;
            $centerLng = 74.5698;
            
            // Генерируем случайные координаты в радиусе 5 км от центра
            $radiusInDegrees = 0.05; // примерно 5 км
            
            // Если координаты не установлены, генерируем случайные
            if (!$driver->lat || !$driver->lng) {
                $driver->lat = $centerLat + (mt_rand(-1000, 1000) / 10000) * $radiusInDegrees;
                $driver->lng = $centerLng + (mt_rand(-1000, 1000) / 10000) * $radiusInDegrees;
            }
            
            return $driver;
        });
        
        return $drivers;
    }

    /**
     * Возвращает список доступных тарифов в формате JSON
     */
    public function getTariffsJson(Request $request)
    {
        // Получаем уникальные тарифы из таблицы водителей
        $driverTariffs = Driver::whereNotNull('tariff')
                        ->distinct()
                        ->pluck('tariff')
                        ->toArray();
        
        // Формируем структуру данных для тарифов
        $tariffs = [];
        
        // Базовая настройка тарифов
        $tariffConfig = [
            'Эконом' => ['base_price' => 50, 'price_per_km' => 10],
            'Люкс' => ['base_price' => 100, 'price_per_km' => 15],
            'Бизнес' => ['base_price' => 150, 'price_per_km' => 20],
            'Комфорт' => ['base_price' => 80, 'price_per_km' => 12],
            'Премиум' => ['base_price' => 200, 'price_per_km' => 25]
        ];
        
        // Создаем объекты тарифов
        foreach ($driverTariffs as $index => $tariffName) {
            $config = $tariffConfig[$tariffName] ?? ['base_price' => 50, 'price_per_km' => 10];
            
            $tariffs[] = [
                'id' => $index + 1,
                'name' => $tariffName,
                'base_price' => $config['base_price'],
                'price_per_km' => $config['price_per_km']
            ];
        }
        
        // Если список тарифов пуст, добавляем стандартные
        if (empty($tariffs)) {
            $tariffs = [
                ['id' => 1, 'name' => 'Эконом', 'base_price' => 50, 'price_per_km' => 10],
                ['id' => 2, 'name' => 'Люкс', 'base_price' => 100, 'price_per_km' => 15],
                ['id' => 3, 'name' => 'Бизнес', 'base_price' => 150, 'price_per_km' => 20]
            ];
        }
        
        return response()->json($tariffs);
    }

    /**
     * Возвращает общий баланс всех водителей через API
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalBalanceApi()
    {
        $totalBalance = $this->getTotalBalance();
        return response()->json([
            'success' => true,
            'total_balance' => $totalBalance
        ]);
    }

    /**
     * Отображает страницу с водителями
     */
    public function driversPage()
    {
        $totalDrivers = Driver::count();
        $online = Driver::where('status', 'online')->count();
        $free   = Driver::where('status', 'free')->count();
        $busy   = Driver::where('status', 'busy')->count();
        $drivers = Driver::paginate(50);
        $totalBalance = $this->getTotalBalance();
        
        try {
            return view('disp.drivers', compact('totalDrivers', 'online', 'free', 'busy', 'drivers', 'totalBalance'));
        } catch (\Exception $e) {
            // Выводим ошибку напрямую для диагностики
            return '<h1>Ошибка при отображении страницы</h1>
                    <p>' . $e->getMessage() . '</p>
                    <pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    /**
     * Возвращает список заказов в формате JSON
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrdersJson(Request $request)
    {
        // Проверяем наличие модели Order
        if (!class_exists('App\Models\Order')) {
            // Если модель не существует, возвращаем тестовые данные
            return response()->json([
                'success' => true,
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10
            ]);
        }
        
        try {
            // Получаем параметры запроса
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $status = $request->input('status');
            $search = $request->input('search');
            
            // Формируем запрос
            $query = \App\Models\Order::query();
            
            // Применяем фильтры, если они указаны
            if ($status) {
                $query->where('status', $status);
            }
            
            // Применяем поиск, если указан
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('client_name', 'like', "%{$search}%")
                      ->orWhere('origin_street', 'like', "%{$search}%")
                      ->orWhere('destination_street', 'like', "%{$search}%");
                });
            }
            
            // Получаем общее количество записей после применения фильтров
            $total = $query->count();
            
            // Получаем заказы с пагинацией
            $orders = $query->orderBy('created_at', 'desc')
                           ->skip(($page - 1) * $perPage)
                           ->take($perPage)
                           ->get();
            
            return response()->json([
                'success' => true,
                'data' => $orders,
                'total' => $total,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'per_page' => $perPage
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении заказов: ' . $e->getMessage());
            
            // В случае ошибки возвращаем пустой список
            return response()->json([
                'success' => true,
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 10
            ]);
        }
    }

    /**
     * Подготавливает данные о транзакциях для графика
     * 
     * @param string|null $datePreset 
     * @param string|null $startDate 
     * @param string|null $endDate
     * @return array Массив с данными о транзакциях
     */
    private function getTransactionsData($datePreset, $startDate, $endDate)
    {
        // Проверяем существование модели Transaction
        if (!class_exists('App\\Models\\Transaction') || !Schema::hasTable('transactions')) {
            \Log::info('Таблица transactions не существует, используем пустые данные');
            
            // Возвращаем пустые данные, если таблица не существует
            return [
                'count' => 0,
                'sum' => 0,
                'labels' => ['Нет данных'],
                'values' => [0]
            ];
        }
        
        try {
            // Формируем запрос для транзакций с учетом фильтров
            $query = \App\Models\Transaction::where('transaction_type', 'deposit')
                ->where('status', 'completed');
            
            // Применяем фильтры по дате
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                $start = \Carbon\Carbon::parse($startDate);
                $end = \Carbon\Carbon::parse($endDate);
            } elseif ($datePreset) {
                $today = now()->format('Y-m-d');
                
                switch ($datePreset) {
                    case 'today':
                        $query->whereDate('created_at', $today);
                        $start = \Carbon\Carbon::parse($today);
                        $end = $start->copy();
                        break;
                    case 'yesterday':
                        $yesterday = now()->subDay()->format('Y-m-d');
                        $query->whereDate('created_at', $yesterday);
                        $start = \Carbon\Carbon::parse($yesterday);
                        $end = $start->copy();
                        break;
                    case 'week':
                        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
                        $query->whereBetween('created_at', [
                            $startOfWeek . ' 00:00:00',
                            $today . ' 23:59:59'
                        ]);
                        $start = \Carbon\Carbon::parse($startOfWeek);
                        $end = \Carbon\Carbon::parse($today);
                        break;
                    case 'month':
                        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                        $query->whereBetween('created_at', [
                            $startOfMonth . ' 00:00:00',
                            $today . ' 23:59:59'
                        ]);
                        $start = \Carbon\Carbon::parse($startOfMonth);
                        $end = \Carbon\Carbon::parse($today);
                        break;
                }
            } else {
                // Если нет фильтров, используем все доступные транзакции
                // Найдем самую раннюю и самую позднюю транзакции
                $firstTransaction = $query->orderBy('created_at')->first();
                $lastTransaction = $query->orderBy('created_at', 'desc')->first();
                
                if ($firstTransaction && $lastTransaction) {
                    $start = \Carbon\Carbon::parse($firstTransaction->created_at)->startOfDay();
                    $end = \Carbon\Carbon::parse($lastTransaction->created_at)->endOfDay();
                    
                    // Если всего одна транзакция или все транзакции в один день
                    if ($start->isSameDay($end)) {
                        $start = $start->copy()->subDays(3);
                        $end = $end->copy()->addDays(3);
                    }
                } else {
                    // Нет транзакций, используем последнюю неделю
                    $end = now();
                    $start = now()->subDays(6);
                    
                    return [
                        'count' => 0,
                        'sum' => 0,
                        'labels' => ['Нет транзакций'],
                        'values' => [0]
                    ];
                }
            }
            
            // Получаем общую сумму и количество транзакций
            $count = $query->count();
            $sum = $query->sum('amount');
            
            // Получаем данные о суммах транзакций по дням
            $dailyData = \App\Models\Transaction::where('transaction_type', 'deposit')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start->format('Y-m-d') . ' 00:00:00', $end->format('Y-m-d') . ' 23:59:59'])
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date')
                ->toArray();
            
            // Формируем данные для графика
            $labels = [];
            $values = [];
            
            $current = $start->copy();
            while ($current <= $end) {
                $dateStr = $current->format('Y-m-d');
                // Форматируем дату для отображения в формате день.месяц
                $labels[] = $current->format('d.m');
                $values[] = isset($dailyData[$dateStr]) ? round($dailyData[$dateStr]['total'], 2) : 0;
                $current->addDay();
            }
            
            return [
                'count' => $count,
                'sum' => $sum,
                'labels' => $labels,
                'values' => $values
            ];
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении данных о транзакциях: ' . $e->getMessage());
            
            // В случае ошибки возвращаем тестовые данные
            return [
                'count' => 0,
                'sum' => 0,
                'labels' => ['Нет данных'],
                'values' => [0]
            ];
        }
    }

    /**
     * Удаляет водителя из системы
     *
     * @param  int  $driverId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDriver($driverId)
    {
        try {
            $driver = Driver::findOrFail($driverId);
            
            // Проверяем, существует ли таблица orders перед проверкой активных поездок
            $hasActiveTrips = false;
            if (Schema::hasTable('orders')) {
                $hasActiveTrips = DB::table('orders')
                    ->where('driver_id', $driverId)
                    ->whereIn('status', ['accepted', 'in_progress'])
                    ->exists();
            }
                
            if ($hasActiveTrips) {
                return response()->json([
                    'success' => false,
                    'message' => 'Невозможно удалить водителя с активными поездками'
                ], 400);
            }
            
            // Создаем запись в журнале, если таблица существует
            if (Schema::hasTable('activity_log')) {
                DB::table('activity_log')->insert([
                    'log_name' => 'drivers',
                    'description' => 'Удален водитель #' . $driverId . ' - ' . $driver->full_name,
                    'subject_type' => 'App\Models\Driver',
                    'subject_id' => $driverId,
                    'causer_type' => 'dispatcher',
                    'causer_id' => session('dispatcher_id', 0),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Удаляем водителя
            $driver->delete();
            
            // Обновляем кеш общего баланса
            $this->updateTotalBalanceCache();
            
            // Создаем уведомление об удалении
            app(\App\Http\Controllers\NotificationController::class)->create(
                'deletion',
                'Водитель удален',
                "Водитель {$driver->full_name} был удален из системы",
                ['driver_id' => $driverId]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Водитель успешно удален'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении водителя: ' . $e->getMessage()
            ], 500);
        }
    }
}