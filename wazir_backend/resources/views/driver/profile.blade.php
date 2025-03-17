<!DOCTYPE html>
@php
use Illuminate\Support\Facades\Schema;
// Проверяем существование таблицы driver_vehicles
$driverVehicleTableExists = Schema::hasTable('driver_vehicles');
@endphp
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль водителя - Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .profile-container {
        padding: 30px 0;
    }

    .profile-header {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f0f0f0;
        margin-right: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #888;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .profile-status {
        display: inline-block;
        background: #4CAF50;
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .profile-contact {
        color: #666;
        font-size: 14px;
    }

    .profile-section {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .stat-item {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .menu-item {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .menu-item:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {

        .stats-grid,
        .menu-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>
    <header>
        <div class="brand">
            <div class="container">
                <div class="brand__content">
                    <div class="logo">
                        <img src="{{ asset('assets/img/driver/logo.png') }}" alt="logo">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <section>
            <div class="container">
                <div class="profile-container">
                    <!-- Шапка профиля -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            {{ substr($driver->full_name ?? 'Водитель', 0, 1) }}
                        </div>
                        <div class="profile-info">
                            <div class="profile-name">{{ $driver->full_name ?? 'Имя водителя' }}</div>
                            <div class="profile-status" style="
                                @if($driver->status == 'online') background: #4CAF50; 
                                @elseif($driver->status == 'busy') background: #FFC107; 
                                @elseif($driver->status == 'offline') background: #9E9E9E;
                                @else background: #4CAF50; @endif">
                                @if($driver->status == 'online') Онлайн
                                @elseif($driver->status == 'busy') Занят
                                @elseif($driver->status == 'offline') Офлайн
                                @else Активный @endif
                            </div>
                            <div class="profile-contact">{{ $driver->phone ?? '+996 XXX XXX XXX' }}</div>
                        </div>
                    </div>

                    <!-- Баланс -->
                    <div class="profile-balance"
                        style="background: #f7f7f7; padding: 15px; margin: 15px 0; border-radius: 8px; text-align: center;">
                        <div style="font-size: 18px; font-weight: bold; color: #333;">Баланс:
                            {{ number_format($driver->balance ?? 0, 0, ',', ' ') }} ₸</div>
                    </div>

                    <!-- Статистика -->
                    <div class="profile-section">
                        <h2 class="section-title">Моя статистика</h2>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">0</div>
                                <div class="stat-label">Выполнено заказов</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">0 ₸</div>
                                <div class="stat-label">Заработано</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">5.0</div>
                                <div class="stat-label">Средний рейтинг</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">0 ч</div>
                                <div class="stat-label">Время в линии</div>
                            </div>
                        </div>
                    </div>

                    <!-- Информация о водителе -->
                    <div class="profile-section">
                        <h2 class="section-title">Личная информация</h2>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;">
                                    <strong>Телефон:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->phone ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Дата
                                        рождения:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->formatted_birth_date ?? 'Не указана' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Номер
                                        лицензии:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->license_number ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Срок действия
                                        лицензии:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    @if ($driver->license_expiry_date)
                                    До {{ \Carbon\Carbon::parse($driver->license_expiry_date)->format('d.m.Y') }}
                                    @else
                                    Не указан
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Позывной:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->callsign ?? 'Не присвоен' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Тип сервиса:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->service_type ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Категория:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->category ?? 'Стандарт' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Тариф:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->tariff ?? 'Базовый' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Информация об автомобиле -->
                    <div class="profile-section">
                        <h2 class="section-title">Мой автомобиль</h2>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee; width: 40%;">
                                    <strong>Марка:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->car_brand ?? 'Не указана' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Модель:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->car_model ?? 'Не указана' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Цвет:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->car_color ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Год выпуска:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->car_year ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Гос. номер:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->license_plate ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>VIN:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->vin ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Номер
                                        кузова:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->body_number ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>СТС:</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->sts ?? 'Не указан' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Трансмиссия:</strong>
                                </td>
                                <td style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                    {{ $driver->transmission ?? 'Не указана' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- История транзакций -->
                    <div class="profile-section">
                        <h2 class="section-title">История транзакций</h2>
                        <div style="max-height: 200px; overflow-y: auto; border: 1px solid #eee; border-radius: 8px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f5f5f5;">
                                        <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd;">Дата
                                        </th>
                                        <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd;">
                                            Описание</th>
                                        <th style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">
                                            Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($driver->transactions && $driver->transactions->count() > 0)
                                    @foreach($driver->transactions as $transaction)
                                    <tr>
                                        <td style="padding: 8px; border-bottom: 1px solid #eee;">
                                            {{ $transaction->formatted_date }}</td>
                                        <td style="padding: 8px; border-bottom: 1px solid #eee;">
                                            {{ $transaction->description }}</td>
                                        <td
                                            style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; 
                                                @if($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund') color: #28a745; @else color: #dc3545; @endif">
                                            {{ ($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'refund' ? '+' : '-') }}{{ number_format($transaction->amount, 0, ',', ' ') }}
                                            ₸
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3" style="padding: 15px; text-align: center; color: #888;">История
                                            транзакций пуста</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Меню -->
                    <div class="profile-section">
                        <h2 class="section-title">Меню</h2>
                        <div class="menu-grid">
                            <a href="#" class="menu-item">Мои данные</a>
                            <a href="#" class="menu-item">Мой автомобиль</a>
                            <a href="#" class="menu-item">История заказов</a>
                            <a href="#" class="menu-item">Настройки</a>
                            <a href="#" class="menu-item">Поддержка</a>
                            <a href="{{ route('driver.logout') }}" class="menu-item">Выйти</a>
                        </div>
                    </div>

                    <div class="buttons-container" style="text-align: center; margin-top: 20px;">
                        <a href="{{ route('driver.index') }}" class="main__btn-active">Начать работу</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>