<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\DriverCreationController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('client')->group(function () {
    Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.index');
});

Route::prefix('disp')->group(function () {
    Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.index');
    Route::get('/drivers', [DispatcherController::class, 'index'])->name('dispatcher.drivers');
    Route::get('/drivers/list', [DispatcherController::class, 'list'])->name('dispatcher.drivers.list');
});

Route::prefix('driver')->group(function () {
    Route::get('/', function () {
        return view('driver.index');
    });
});

Route::group(['prefix' => 'backend'], function() {
    Route::get('/driver/auth/step1', [DriverAuthController::class, 'showStep1'])->name('driver.auth.step1');
    Route::post('/driver/auth/step1', [DriverAuthController::class, 'processStep1'])->name('driver.auth.processStep1');
    Route::get('/driver/auth/step2', [DriverAuthController::class, 'showStep2'])->name('driver.auth.step2');
    Route::post('/driver/auth/step2', [DriverAuthController::class, 'processStep2'])->name('driver.auth.processStep2');
    Route::get('/driver/auth/step3', [DriverAuthController::class, 'showStep3'])->name('driver.auth.step3');
    Route::post('/driver/auth/step3', [DriverAuthController::class, 'processStep3'])->name('driver.auth.processStep3');

    Route::prefix('disp')->group(function() {
        Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.backend.index');
        Route::get('/drivers', [DispatcherController::class, 'index'])->name('dispatcher.backend.drivers');
        Route::get('/drivers/list', [DispatcherController::class, 'list'])->name('dispatcher.backend.drivers.list');

        Route::get('/analytics', [DispatcherController::class, 'analytics'])->name('dispatcher.backend.analytics');
        
        Route::get('/get_balance', function() {
            return view('disp.get_balance');
        })->name('dispatcher.backend.get_balance');
        Route::get('/new_order', function() {
            return view('disp.new_order');
        })->name('dispatcher.backend.new_order');

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

        Route::get('/drivers_control', function() {
            return view('disp.drivers_control');
        })->name('dispatcher.backend.drivers_control');
        Route::get('/cars', [DispatcherController::class, 'cars'])->name('dispatcher.backend.cars');
        Route::get('/chat', [DispatcherController::class, 'chat'])->name('dispatcher.backend.chat');
        Route::get('/pay_balance', [DispatcherController::class, 'pay_balance'])->name('dispatcher.backend.pay_balance');
        Route::get('/cars/list', [DispatcherController::class, 'getCarsList'])
            ->name('dispatcher.backend.cars.list');

        // Маршруты для чата
        Route::get('/chat/list', [App\Http\Controllers\DispatcherController::class, 'getChatList'])
            ->name('dispatcher.backend.chat.list');
        Route::get('/chat/{chatId}/messages', [App\Http\Controllers\DispatcherController::class, 'getChatMessages']);
        Route::post('/chat/{chatId}/send', [App\Http\Controllers\DispatcherController::class, 'sendChatMessage']);
        Route::post('/chat/{chatId}/mark-read', [App\Http\Controllers\DispatcherController::class, 'markChatAsRead']);
    });
});

Route::get('/test-db', function() {
    dd(\App\Models\Driver::all());
});