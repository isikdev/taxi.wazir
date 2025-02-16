@extends('disp.layout')
@section('title', 'Аналитика Диспетчерская - taxi.wazir.kg')
@section('content')
@php
$percentage = ($total > 0) ? round(($confirmed / $total) * 100) : 0;
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
                        <th>Выполнено заказов</th>
                        <th>Типы заказов</th>
                        <th>Категории заказов</th>
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