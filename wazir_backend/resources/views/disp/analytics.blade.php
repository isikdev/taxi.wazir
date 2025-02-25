@extends('disp.layout')
@section('title', 'Аналитика Диспетчерская - taxi.wazir.kg')
@section('content')
@php
$percentage = ($total > 0) ? round(($confirmed / $total) * 100) : 0;

// TODO: Добавить в контроллер получение данных о зарегистрированных автомобилях:
// $active_cars = Car::where('is_active', true)->count(); // или другое условие активности
// $inactive_cars = Car::where('is_active', false)->count();
// $total_cars = $active_cars + $inactive_cars;
// $cars_percentage = ($total_cars > 0) ? round(($active_cars / $total_cars) * 100) : 0;
//
// Добавить эти переменные в compact() или в массив данных, передаваемых в шаблон
// После этого раскомментировать код ниже для отображения данных

// Временная заглушка для тестирования отображения
$active_cars = 15; // Заменить на реальные данные из БД
$inactive_cars = 5; // Заменить на реальные данные из БД
$total_cars = $active_cars + $inactive_cars;
$cars_percentage = ($total_cars > 0) ? round(($active_cars / $total_cars) * 100) : 0;
@endphp

<style>
.donut-chart {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: conic-gradient(#2e5375 calc(var(--percent) * 1%),
            #7f2f2f 0);
    position: relative;
}

.donut-center-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
    color: #fff;
}
</style>
<div class="main__analytics">
    <div class="top-bar">
        <div class="main__table">
            <table>
                <thead>
                    <tr>
                        <th>Зарегистрированные водители</th>
                        <th>Зарегистрированные авто</th>
                        <th>Зарегистрированные водители</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="charts">
        <div class="main__table">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="chart-block">
                                <div class="donut-container">
                                    <div class="donut-chart donut-1" style="--percent: {{ $percentage }};">
                                        <div class="donut-center-text">{{ $percentage }}%</div>
                                    </div>
                                </div>
                                <div class="chart-legend">
                                    <div class="chart-legend__total">
                                        Всего: <span>{{ $total }}</span>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--blue"></div>
                                        <div>Подтверждённые</div>
                                        <div>{{ $confirmed }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--red"></div>
                                        <div>Неподтверждённые</div>
                                        <div>{{ $unconfirmed }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="chart-block">
                                <div class="donut-container">
                                    <div class="donut-chart donut-1" style="--percent: {{ $cars_percentage }};">
                                        <div class="donut-center-text">{{ $cars_percentage }}%</div>
                                    </div>
                                </div>
                                <div class="chart-legend">
                                    <div class="chart-legend__total">
                                        Всего: <span>{{ $total_cars }}</span>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--blue"></div>
                                        <div>Активные</div>
                                        <div>{{ $active_cars }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--red"></div>
                                        <div>Неактивные</div>
                                        <div>{{ $inactive_cars }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="chart-block">
                                <div class="donut-container">
                                    <div class="donut-chart donut-1" style="--percent: {{ $percentage }};">
                                        <div class="donut-center-text">{{ $percentage }}%</div>
                                    </div>
                                </div>
                                <div class="chart-legend">
                                    <div class="chart-legend__total">
                                        Всего: <span>{{ $total }}</span>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--blue"></div>
                                        <div>Подтверждённые</div>
                                        <div>{{ $confirmed }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div class="legend-color legend-color--red"></div>
                                        <div>Неподтверждённые</div>
                                        <div>{{ $unconfirmed }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection