<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\DriverCreationController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DriverApplicationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\DispatcherAuth;

// Перенаправляем корневой URL на страницу логина диспетчера
Route::get('/', function () {
    return redirect()->route('dispatcher.login');
});

// Маршруты для клиентов
Route::prefix('client')->group(function () {
    Route::get('/', [DispatcherController::class, 'index'])->name('client.dispatcher.index');
});

// Маршруты для входа диспетчера (доступны без авторизации)
Route::get('/disp/login', [DispatcherController::class, 'showLogin'])->name('dispatcher.login');
Route::post('/disp/login', [DispatcherController::class, 'processLogin'])->name('dispatcher.login.process');
Route::get('/disp/logout', [DispatcherController::class, 'logout'])->name('dispatcher.logout');

// ВСЕ маршруты для диспетчера защищены middleware dispatcher.auth
Route::middleware(\App\Http\Middleware\DispatcherAuthMiddleware::class)->group(function () {
    // Основные маршруты диспетчера
    Route::prefix('disp')->group(function () {
        Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.index');
        Route::get('/drivers', [DispatcherController::class, 'index'])->name('dispatcher.drivers');
        Route::get('/drivers/list', [DispatcherController::class, 'list'])->name('dispatcher.drivers.list');
        Route::get('/maps', function() {
            return view('disp.maps');
        })->name('dispatcher.maps');
    });

    // Маршруты для бэкенда диспетчера
    Route::prefix('disp/backend')->group(function () {
        Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.backend.index');
        Route::get('/analytics', [DispatcherController::class, 'analytics'])->name('dispatcher.backend.analytics');
        Route::get('/get_balance', [DispatcherController::class, 'get_balance'])->name('dispatcher.backend.get_balance');
        Route::get('/pay_balance', [DispatcherController::class, 'pay_balance'])->name('dispatcher.backend.pay_balance');
        Route::post('/process_balance_payment', [DispatcherController::class, 'processBalancePayment'])->name('dispatcher.backend.process_balance_payment');
        Route::get('/drivers', [DispatcherController::class, 'drivers'])->name('dispatcher.backend.drivers');
        Route::get('/new_order', [DispatcherController::class, 'new_order'])->name('dispatcher.backend.new_order');

        // Маршруты для управления водителями и API
        Route::post('/order/create', [OrderController::class, 'create'])->name('dispatcher.backend.order.create');
        Route::get('/order/details/{id}', [OrderController::class, 'getOrderDetails'])->name('dispatcher.backend.order.details');
        Route::get('/order/drivers-nearby', [OrderController::class, 'getDriversNearby'])->name('dispatcher.backend.order.drivers-nearby');
        Route::post('/order/assign', [OrderController::class, 'assignOrder'])->name('dispatcher.backend.order.assign');
        Route::get('/drivers/list', [DispatcherController::class, 'getDriversList'])->name('dispatcher.backend.drivers.list');
        Route::get('/drivers/json', [DispatcherController::class, 'getDriversJson'])->name('dispatcher.backend.drivers.json');
        Route::delete('/drivers/{driver}/delete', [DispatcherController::class, 'deleteDriver'])->name('dispatcher.backend.drivers.delete');
        
        // Добавляем маршрут для получения тарифов
        Route::get('/tariffs/json', [DispatcherController::class, 'getTariffsJson'])->name('dispatcher.backend.tariffs.json');
        
        // Добавляем маршрут для получения заказов в формате JSON
        Route::get('/orders/json', [DispatcherController::class, 'getOrdersJson'])->name('dispatcher.backend.orders.json');

        // Добавляем маршрут для получения общего баланса
        Route::get('/get_total_balance', [DispatcherController::class, 'getTotalBalanceApi'])->name('dispatcher.backend.get_total_balance');

        // Маршруты для управления заявками водителей
        Route::get('/driver-applications', [DispatcherController::class, 'driverApplications'])->name('dispatcher.backend.driver-applications');
        Route::post('/driver-applications/{driverId}/approve', [DispatcherController::class, 'approveApplication'])->name('dispatcher.backend.approve-application');
        Route::post('/driver-applications/{driverId}/reject', [DispatcherController::class, 'rejectApplication'])->name('dispatcher.backend.reject-application');

        // Маршруты для DriverCreationController
        Route::get('/drivers_control_edit', [DriverCreationController::class, 'showStep1'])
            ->name('dispatcher.backend.drivers_control_edit');
        Route::post('/drivers_control_edit', [DriverCreationController::class, 'processStep1'])
            ->name('dispatcher.backend.process_drivers_control_edit');

        Route::get('/drivers_num_edit/{driver}', [DriverCreationController::class, 'showStep2'])
            ->name('dispatcher.backend.drivers_num_edit');
        Route::post('/drivers_num_edit/{driver}', [DriverCreationController::class, 'processStep2'])
            ->name('dispatcher.backend.process_drivers_num_edit');

        Route::get('/drivers_car_edit/{driver}', [DriverCreationController::class, 'showStep3'])
            ->name('dispatcher.backend.drivers_car_edit');
        Route::post('/drivers_car_edit/{driver}', [DriverCreationController::class, 'processStep3'])
            ->name('dispatcher.backend.process_drivers_car_edit');

        // Маршруты для управления заявками водителей
        Route::get('/drivers_control', [DriverApplicationController::class, 'index'])
            ->name('dispatcher.backend.drivers_control');
        Route::get('/driver-application/{id}', [DriverApplicationController::class, 'getApplicationDetails'])
            ->name('dispatcher.backend.driver-application-details');
        Route::post('/driver-application/{id}/approve', [DriverApplicationController::class, 'approveApplication'])
            ->name('dispatcher.backend.approve-application');
        Route::post('/driver-application/{id}/reject', [DriverApplicationController::class, 'rejectApplication'])
            ->name('dispatcher.backend.reject-application');

        Route::get('/cars', [DispatcherController::class, 'cars'])->name('dispatcher.backend.cars');
        Route::get('/chat', [DispatcherController::class, 'chat'])->name('dispatcher.backend.chat');
        Route::get('/cars/list', [DispatcherController::class, 'getCarsList'])
            ->name('dispatcher.backend.cars.list');

        // Маршруты для чата
        Route::get('/chat/list', [DispatcherController::class, 'getChatList'])
            ->name('dispatcher.backend.chat.list');
        Route::get('/chat/{chatId}/messages', [DispatcherController::class, 'getChatMessages']);
        Route::post('/chat/{chatId}/send', [DispatcherController::class, 'sendChatMessage']);
        Route::post('/chat/{chatId}/mark-read', [DispatcherController::class, 'markChatAsRead']);

        // Добавляем маршрут для карты
        Route::get('/maps', function() {
            $totalBalance = \Illuminate\Support\Facades\Cache::remember('total_balance', 60, function () {
                return \App\Models\Driver::sum('balance');
            });
            return view('disp.maps', compact('totalBalance'));
        })->name('dispatcher.backend.maps');

        // Маршруты для системы уведомлений
        Route::prefix('api/notifications')->group(function () {
            Route::get('/unread', [NotificationController::class, 'getUnread']);
            Route::get('/all', [NotificationController::class, 'getAll']);
            Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        });
    });
});

// Маршруты для водителей
Route::prefix('driver')->group(function () {
    Route::get('/', function () {
        return view('driver.index');
    })->name('driver.index');
    
    // Страница входа
    Route::get('/login', function() {
        return view('driver.login');
    })->name('driver.login');
    
    Route::post('/login', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);
        
        // В реальном приложении здесь должна быть настоящая аутентификация
        // Сейчас просто ищем водителя по номеру телефона
        $driver = \App\Models\Driver::where('phone', $request->phone)->first();
        
        if ($driver) {
            // Сохраняем ID водителя в сессии
            session(['driver_id' => $driver->id]);
            
            return redirect()->route('driver.index');
        }
        
        return back()->withErrors(['phone' => 'Неверный номер телефона или пароль']);
    })->name('driver.login.process');
    
    // Маршрут для профиля водителя
    Route::get('/profile', function() {
        $driverId = session('driver_id');
        
        // Если ID не найден в сессии, перенаправляем на страницу входа
        if (!$driverId) {
            return redirect()->route('driver.login');
        }
        
        try {
            // Проверяем существование таблицы driver_vehicles
            $driverVehicleTableExists = \Illuminate\Support\Facades\Schema::hasTable('driver_vehicles');
            
            // Получаем водителя
            $driver = \App\Models\Driver::query();
            
            // Добавляем связь с автомобилем только если таблица существует
            if ($driverVehicleTableExists) {
                $driver->with(['vehicle']);
            }
            
            // Добавляем связь с транзакциями
            $driver->with(['transactions' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(20);
            }]);
            
            // Получаем результат
            $driver = $driver->find($driverId);
            
            if (!$driver) {
                // Водитель не найден, очищаем сессию и перенаправляем на страницу входа
                session()->forget('driver_id');
                return redirect()->route('driver.login');
            }
            
            // Проверяем статус анкеты
            if ($driver->survey_status !== 'approved') {
                return redirect()->route('driver.survey.application-status');
            }
            
            // Форматируем дату правильно, если она есть
            if ($driver->date_of_birth) {
                $driver->formatted_birth_date = \Carbon\Carbon::parse($driver->date_of_birth)->format('d.m.Y');
            }
            
            return view('driver.profile', ['driver' => $driver]);
        } catch (\Exception $e) {
            // Логируем ошибку
            \Illuminate\Support\Facades\Log::error('Ошибка при загрузке профиля: ' . $e->getMessage());
            
            // Возвращаем страницу с ошибкой
            return view('driver.error', ['error' => $e->getMessage()]);
        }
    })->name('driver.profile');
    
    // Маршрут для выхода из системы
    Route::get('/logout', function() {
        session()->forget('driver_id');
        return redirect()->route('driver.login');
    })->name('driver.logout');
});

Route::group(['prefix' => 'backend'], function() {
    Route::get('/driver/auth/step1', [DriverAuthController::class, 'showStep1'])->name('driver.auth.step1');
    Route::post('/driver/auth/step1', [DriverAuthController::class, 'processStep1'])->name('driver.auth.processStep1');
    Route::get('/driver/auth/step2', [DriverAuthController::class, 'showStep2'])->name('driver.auth.step2');
    Route::post('/driver/auth/step2', [DriverAuthController::class, 'processStep2'])->name('driver.auth.processStep2');
    Route::get('/driver/auth/step3', [DriverAuthController::class, 'showStep3'])->name('driver.auth.step3');
    Route::post('/driver/auth/step3', [DriverAuthController::class, 'processStep3'])->name('driver.auth.processStep3');

    // Маршруты для анкеты водителя
    Route::prefix('driver/survey')->name('driver.survey.')->middleware('web')->group(function () {
        Route::get('/', [SurveyController::class, 'redirectToLastStep'])->name('index');
        
        Route::get('/step1', [SurveyController::class, 'showStep1'])->name('step1');
        Route::post('/step1', [SurveyController::class, 'processStep1'])->name('processStep1');
        
        Route::get('/step2', [SurveyController::class, 'showStep2'])->name('step2');
        Route::post('/step2', [SurveyController::class, 'processStep2'])->name('processStep2');
        
        Route::get('/step3', [SurveyController::class, 'showStep3'])->name('step3');
        Route::post('/step3', [SurveyController::class, 'processStep3'])->name('processStep3');
        
        Route::get('/step4', [SurveyController::class, 'showStep4'])->name('step4');
        Route::post('/step4', [SurveyController::class, 'processStep4'])->name('processStep4');
        
        Route::get('/step5', [SurveyController::class, 'showStep5'])->name('step5');
        Route::post('/step5', [SurveyController::class, 'processStep5'])->name('processStep5');
        
        Route::get('/step6', [SurveyController::class, 'showStep6'])->name('step6');
        Route::get('/process-step6/{park_id}', [SurveyController::class, 'processStep6'])->name('processStep6');
        
        Route::get('/step7', [SurveyController::class, 'showStep7'])->name('step7');
        Route::post('/step7', [SurveyController::class, 'processStep7'])->name('processStep7');
        
        Route::get('/step7_terms', [SurveyController::class, 'showStep7Terms'])->name('step7_terms');
        
        Route::get('/step8', [SurveyController::class, 'showStep8'])->name('step8');
        Route::post('/step8', [SurveyController::class, 'processStep8'])->name('processStep8');
        
        Route::get('/complete', [SurveyController::class, 'complete'])->name('complete');
        Route::post('/submit-application', [SurveyController::class, 'submitApplication'])->name('submitApplication');
        
        // Статус анкеты
        Route::get('/application-status', [SurveyController::class, 'applicationStatus'])->name('applicationStatus');
    });
});

// Тестовые маршруты
Route::get('/test-db', function() {
    dd(\App\Models\Driver::all());
});

Route::get('/test-page', function() {
    return 'Тестовая страница работает!';
});

// Тестовый маршрут для диагностики
Route::get('/test-tables', [App\Http\Controllers\TestController::class, 'testTables']);