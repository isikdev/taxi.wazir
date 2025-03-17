use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\DispNotificationController;

Route::get('/', function () {
    return response()->json(['message' => 'Laravel API работает!']);
});

// Маршруты для заказов
Route::prefix('orders')->group(function () {
    Route::get('/', 'App\Http\Controllers\OrderController@index');
    Route::post('/', 'App\Http\Controllers\OrderController@store');
    Route::get('/{id}', 'App\Http\Controllers\OrderController@show');
    Route::patch('/{id}/status', 'App\Http\Controllers\OrderController@updateStatus');
});

// Маршруты для водителей
Route::prefix('drivers')->group(function () {
    Route::get('/', 'App\Http\Controllers\DriverController@index');
    Route::get('/{id}', 'App\Http\Controllers\DriverController@show');
});

/**
 * Маршрут для получения списка заказов
 * Временно возвращает тестовые данные
 */
Route::get('/orders', function () {
    $page = request()->input('page', 1);
    $perPage = request()->input('per_page', 10);
    
    // Создаем тестовые данные для заказов
    $testOrders = [];
    for ($i = 1; $i <= 20; $i++) {
        $status = ['new', 'in_progress', 'completed', 'cancelled'][rand(0, 3)];
        $testOrders[] = [
            'id' => $i,
            'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
            'status' => $status,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s', strtotime('-' . rand(1, 10) . ' hours')),
            'client_name' => 'Клиент ' . $i,
            'origin_street' => 'ул. Советская, ' . rand(1, 100),
            'origin_house' => rand(1, 100),
            'destination_street' => 'ул. Ленина, ' . rand(1, 100),
            'destination_house' => rand(1, 100),
            'price' => rand(100, 1000),
            'driver' => [
                'id' => rand(1, 10),
                'full_name' => 'Водитель ' . rand(1, 10)
            ]
        ];
    }
    
    // Разбиваем на страницы
    $total = count($testOrders);
    $offset = ($page - 1) * $perPage;
    $items = array_slice($testOrders, $offset, $perPage);
    
    return response()->json([
        'success' => true,
        'data' => [
            'data' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ]
    ]);
});

// Маршруты для системы уведомлений
Route::prefix('notifications')->group(function () {
    Route::get('/unread', [NotificationController::class, 'getUnread']);
    Route::get('/all', [NotificationController::class, 'getAll']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
});

// Маршруты для системы уведомлений диспетчера - убираем middleware auth
Route::prefix('disp/api/notifications')->group(function () {
    Route::get('/unread', [DispNotificationController::class, 'getUnread']);
    Route::get('/all', [DispNotificationController::class, 'getAll']);
    Route::post('/{id}/read', [DispNotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [DispNotificationController::class, 'markAllAsRead']);
});

