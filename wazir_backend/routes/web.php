<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\DispatcherController;

// Главная страница
Route::get('/', function () {
    return view('welcome');
});

// Статичные разделы
Route::prefix('client')->group(function () {
    Route::get('/', function () {
        return view('client.index');
    });
});

Route::prefix('disp')->group(function () {
    Route::get('/', function () {
        return view('disp.index');
    });
    Route::get('/drivers', [DispatcherController::class, 'index'])->name('dispatcher.drivers');
    Route::get('/drivers/list', [DispatcherController::class, 'list'])->name('dispatcher.drivers.list');
});

Route::prefix('driver')->group(function () {
    Route::get('/', function () {
        return view('driver.index');
    });
});

// Группа для динамических страниц Laravel (бэкенд)
Route::group(['prefix' => 'backend'], function() {
    // Регистрация водителя
    Route::get('/driver/auth/step1', [DriverAuthController::class, 'showStep1'])->name('driver.auth.step1');
    Route::post('/driver/auth/step1', [DriverAuthController::class, 'processStep1'])->name('driver.auth.processStep1');
    Route::get('/driver/auth/step2', [DriverAuthController::class, 'showStep2'])->name('driver.auth.step2');
    Route::post('/driver/auth/step2', [DriverAuthController::class, 'processStep2'])->name('driver.auth.processStep2');
    Route::get('/driver/auth/step3', [DriverAuthController::class, 'showStep3'])->name('driver.auth.step3');
    Route::post('/driver/auth/step3', [DriverAuthController::class, 'processStep3'])->name('driver.auth.processStep3');

    // Диспетчерская часть (динамический вариант)
    Route::get('/disp/drivers', [DispatcherController::class, 'index'])->name('dispatcher.backend.drivers');
    Route::get('/disp/drivers/list', [DispatcherController::class, 'list'])->name('dispatcher.backend.drivers.list');
});
