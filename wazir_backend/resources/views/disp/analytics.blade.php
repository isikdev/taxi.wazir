@extends('disp.layout')
@section('title', 'Аналитика Диспетчерская - taxi.wazir.kg')
@section('content')
@php
$percentage = ($total > 0) ? round(($confirmed / $total) * 100) : 0;
// Все данные теперь приходят из контроллера
@endphp

<style>
.main__subheader-date {
    opacity: .5;
}

.donut-chart {
    width: 200px;
    height: 200px;
    border-radius: 100%;
    background: conic-gradient(#3498db calc(var(--percent) * 1%),
            #e74c3c 0);
    position: relative;
    margin: 0 auto;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    transform: rotate(-90deg);
    transition: all 0.3s ease;
}

.donut-chart:hover {
    transform: rotate(-90deg) scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.donut-chart::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    height: 70%;
    background-color: #2c3e50;
    border-radius: 50%;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.2);
}

.donut-center-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(90deg);
    font-weight: bold;
    font-size: 24px;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    z-index: 2;
}

.chart-block {
    border-radius: 10px;
    transition: all 0.3s ease;
    padding: 15px;
    margin: 10px auto;
    width: 90%;
}

.chart-legend {
    color: #ecf0f1;
    margin-top: 20px;
}

.chart-legend__total {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    text-align: center;
    color: #fff;
}

.chart-legend__total span {
    color: #3498db;
    font-size: 20px;
}

.chart-legend__item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    justify-content: space-between;
    font-size: 16px;
    padding: 8px 10px;
    border-radius: 5px;
    background-color: rgba(0, 0, 0, 0.2);
}

.chart-legend__item:hover {
    background-color: rgba(0, 0, 0, 0.3);
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    margin-right: 8px;
}

.legend-color--blue {
    background-color: #3498db;
    box-shadow: 0 2px 5px rgba(52, 152, 219, 0.5);
}

.legend-color--red {
    background-color: #e74c3c;
    box-shadow: 0 2px 5px rgba(231, 76, 60, 0.5);
}

.main__table thead tr th {
    border-right: 1px solid #818181;
    color: #ecf0f1;
    padding: 12px;
    font-size: 16px;
}

.charts {
    margin-top: 20px;
}
</style>

<div class="main__analytics">
    <div class="top-bar">
        <div class="main__table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 33.33%; text-align: center;">Зарегистрированные водители</th>
                        <th style="width: 33.33%; text-align: center;">Зарегистрированные авто</th>
                        <th style="width: 33.33%; text-align: center;">Статус водителей</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="charts">
        <div class="main__table">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 33.33%;">
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
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--blue"></div>
                                            <div>Подтверждённые</div>
                                        </div>
                                        <div>{{ $confirmed }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--red"></div>
                                            <div>Не подтверждены</div>
                                        </div>
                                        <div>{{ $unconfirmed }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="width: 33.33%;">
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
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--blue"></div>
                                            <div>Активные</div>
                                        </div>
                                        <div>{{ $active_cars }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--red"></div>
                                            <div>Неактивные</div>
                                        </div>
                                        <div>{{ $inactive_cars }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="width: 33.33%;">
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
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--blue"></div>
                                            <div>Подтверждённые</div>
                                        </div>
                                        <div>{{ $confirmed }}</div>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--red"></div>
                                            <div>Не подтверждены</div>
                                        </div>
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

    <!-- Новый блок для графика транзакций -->
    <div class="top-bar" style="margin-top: 30px;">
        <div class="main__table">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th style="text-align: center;">Пополнения баланса водителей (транзакции)</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="charts">
        <div class="main__table">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 100%;">
                            <div class="chart-block transactions-chart">
                                <div style="width: 100%; height: 300px;">
                                    <canvas id="transactionsChart"></canvas>
                                </div>
                                <div class="chart-legend" id="transactions-legend">
                                    <div class="chart-legend__total">
                                        Всего транзакций: <span>{{ $transactions_count ?? 0 }}</span>
                                    </div>
                                    <div class="chart-legend__item">
                                        <div style="display: flex; align-items: center;">
                                            <div class="legend-color legend-color--blue"></div>
                                            <div>Сумма пополнений</div>
                                        </div>
                                        <div>{{ $transactions_sum ?? 0 }} сом</div>
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

@push('scripts')
<!-- Подключаем Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Данные для инициализации графика транзакций -->
<script>
// Данные для графика транзакций, полученные при рендеринге страницы
var transactionsChartData = {
    labels: {
        !!json_encode($transactions_data['labels'] ?? []) !!
    },
    data: {
        !!json_encode($transactions_data['values'] ?? []) !!
    }
};
</script>

<script>
$(document).ready(function() {
    // Определяем функцию применения фильтров для аналитики
    window.applyFilters = function() {
        // Получаем выбранные значения фильтров через общую функцию
        let filterData = window.getFilterParams();

        console.log("Отправляем фильтры:", filterData);

        // Отправляем AJAX запрос
        $.ajax({
            url: "{{ route('dispatcher.backend.analytics') }}",
            method: "GET",
            data: filterData,
            dataType: 'json',
            success: function(response) {
                console.log("Получен ответ:", response);

                // Проверяем структуру ответа
                if (!response || typeof response !== 'object') {
                    console.error('Некорректный формат ответа:', response);
                    return;
                }

                // Обновляем данные на странице
                updateCharts(response);
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при применении фильтров:', error);
                console.error('Статус:', status);
                console.error('Ответ сервера:', xhr.responseText);
                alert('Произошла ошибка при применении фильтров');
            }
        });
    };

    // Функция обновления диаграмм и данных
    function updateCharts(data) {
        try {
            // Обновляем первую диаграмму (водители)
            updateDriversChart(data);

            // Обновляем вторую диаграмму (автомобили)
            updateCarsChart(data);

            // Обновляем график транзакций
            updateTransactionsChart(data);
        } catch (error) {
            console.error('Ошибка при обновлении диаграмм:', error);
        }
    }

    // Обновление диаграммы водителей
    function updateDriversChart(data) {
        try {
            // Проверяем наличие данных
            if (!data.hasOwnProperty('percentage') || !data.hasOwnProperty('total') ||
                !data.hasOwnProperty('confirmed') || !data.hasOwnProperty('unconfirmed')) {
                console.error("Отсутствуют обязательные поля в данных для диаграммы водителей");
                return;
            }

            // Преобразуем значения в целые числа
            let percentage = parseInt(data.percentage) || 0;
            let total = parseInt(data.total) || 0;
            let confirmed = parseInt(data.confirmed) || 0;
            let unconfirmed = parseInt(data.unconfirmed) || 0;

            console.log("Обновляем диаграмму водителей:", {
                percentage: percentage,
                total: total,
                confirmed: confirmed,
                unconfirmed: unconfirmed
            });

            // Обновляем процент в диаграмме
            const donutCharts = $('.donut-chart:eq(0), .donut-chart:eq(2)');
            if (donutCharts.length) {
                donutCharts.css('--percent', percentage);
            }

            const donutTexts = $(
                '.donut-chart:eq(0) .donut-center-text, .donut-chart:eq(2) .donut-center-text');
            if (donutTexts.length) {
                donutTexts.text(percentage + '%');
            }

            // Обновляем HTML для блоков с водителями
            $('.chart-block:eq(0) .chart-legend, .chart-block:eq(2) .chart-legend').html(`
                <div class="chart-legend__total">
                    Всего: <span>${total}</span>
                </div>
                <div class="chart-legend__item">
                    <div style="display: flex; align-items: center;">
                        <div class="legend-color legend-color--blue"></div>
                        <div>Подтверждённые</div>
                    </div>
                    <div>${confirmed}</div>
                </div>
                <div class="chart-legend__item">
                    <div style="display: flex; align-items: center;">
                        <div class="legend-color legend-color--red"></div>
                        <div>Не подтверждены</div>
                    </div>
                    <div>${unconfirmed}</div>
                </div>
            `);

            // Добавляем анимацию обновления
            const chartBlocks = $('.chart-block:eq(0), .chart-block:eq(2)');
            if (chartBlocks.length) {
                chartBlocks.addClass('updated');
                setTimeout(function() {
                    chartBlocks.removeClass('updated');
                }, 1000);
            }
        } catch (error) {
            console.error('Ошибка при обновлении диаграммы водителей:', error);
        }
    }

    // Обновление диаграммы автомобилей
    function updateCarsChart(data) {
        try {
            // Проверяем наличие данных
            if (!data.hasOwnProperty('cars_percentage') || !data.hasOwnProperty('total_cars') ||
                !data.hasOwnProperty('active_cars') || !data.hasOwnProperty('inactive_cars')) {
                console.error("Отсутствуют обязательные поля в данных для диаграммы автомобилей");
                return;
            }

            // Преобразуем значения в целые числа
            let percentage = parseInt(data.cars_percentage) || 0;
            let total = parseInt(data.total_cars) || 0;
            let active = parseInt(data.active_cars) || 0;
            let inactive = parseInt(data.inactive_cars) || 0;

            console.log("Обновляем диаграмму автомобилей:", {
                percentage: percentage,
                total: total,
                active: active,
                inactive: inactive
            });

            // Обновляем процент в диаграмме
            const donutChart = $('.donut-chart:eq(1)');
            if (donutChart.length) {
                donutChart.css('--percent', percentage);
            }

            const donutText = $('.donut-chart:eq(1) .donut-center-text');
            if (donutText.length) {
                donutText.text(percentage + '%');
            }

            // Обновляем весь HTML для блока с автомобилями
            $('.chart-block:eq(1) .chart-legend').html(`
                <div class="chart-legend__total">
                    Всего: <span>${total}</span>
                </div>
                <div class="chart-legend__item">
                    <div style="display: flex; align-items: center;">
                        <div class="legend-color legend-color--blue"></div>
                        <div>Активные</div>
                    </div>
                    <div>${active}</div>
                </div>
                <div class="chart-legend__item">
                    <div style="display: flex; align-items: center;">
                        <div class="legend-color legend-color--red"></div>
                        <div>Неактивные</div>
                    </div>
                    <div>${inactive}</div>
                </div>
            `);

            // Добавляем анимацию обновления
            const chartBlock = $('.chart-block:eq(1)');
            if (chartBlock.length) {
                chartBlock.addClass('updated');
                setTimeout(function() {
                    chartBlock.removeClass('updated');
                }, 1000);
            }
        } catch (error) {
            console.error('Ошибка при обновлении диаграммы автомобилей:', error);
        }
    }

    // Добавляем стиль для анимации обновления
    $('<style>')
        .text(`
            @keyframes chart-update {
                0% { transform: scale(1); }
                50% { transform: scale(1.03); }
                100% { transform: scale(1); }
            }
            .chart-block.updated {
                animation: chart-update 0.5s ease;
                box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
            }
        `)
        .appendTo('head');
});
</script>

<script>
$(document).ready(function() {
    // Инициализация графика транзакций
    function initTransactionsChart() {
        // Подключаем Chart.js если его еще нет
        if (typeof Chart === 'undefined') {
            console.log("Chart.js не загружен. Загружаем библиотеку...");
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = function() {
                console.log("Chart.js успешно загружен!");
                createTransactionsChart();
            };
            script.onerror = function() {
                console.error("Не удалось загрузить Chart.js");
            };
            document.head.appendChild(script);
            return;
        }

        // Если Chart.js уже загружен, создаем график
        createTransactionsChart();
    }

    // Создание графика транзакций
    function createTransactionsChart() {
        console.log("Создаем график транзакций...");
        // Получаем контекст канваса
        const ctx = document.getElementById('transactionsChart');

        if (!ctx) {
            console.error("Не найден элемент canvas для графика транзакций");
            return;
        }

        // Получаем начальные данные из скрытого блока, заполненного при рендеринге страницы
        let initialLabels = [];
        let initialData = [];

        try {
            // Проверяем, есть ли уже данные, переданные с сервера
            if (typeof transactionsChartData !== 'undefined' && transactionsChartData.labels &&
                transactionsChartData.data) {
                console.log("Используем данные с сервера:", transactionsChartData);
                initialLabels = transactionsChartData.labels;
                initialData = transactionsChartData.data;
            } else {
                console.log("Данные с сервера отсутствуют, используем тестовые данные");
                // Используем тестовые данные, если с сервера ничего не пришло
                initialLabels = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль'];
                initialData = [12000, 19000, 3000, 5000, 2000, 3000, 22000];
            }
        } catch (error) {
            console.error('Ошибка при получении начальных данных графика:', error);
            // Используем тестовые данные в случае ошибки
            initialLabels = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль'];
            initialData = [12000, 19000, 3000, 5000, 2000, 3000, 22000];
        }

        // Данные для графика
        const data = {
            labels: initialLabels,
            datasets: [{
                label: 'Пополнения баланса (сом)',
                data: initialData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        };

        // Создаем график
        try {
            // Проверяем и уничтожаем существующий график, если он есть
            if (window.transactionsChart) {
                try {
                    window.transactionsChart.destroy();
                    console.log("Предыдущий график уничтожен");
                } catch (destroyError) {
                    console.warn("Не удалось уничтожить предыдущий график:", destroyError);
                    // Сбрасываем переменную, чтобы не мешала созданию нового графика
                    window.transactionsChart = null;
                }
            }

            // Создаем новый график
            window.transactionsChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#ddd'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#ddd'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ddd'
                            }
                        }
                    }
                }
            });
            console.log("Новый график создан успешно");
        } catch (error) {
            console.error("Ошибка при создании графика:", error);
        }
    }

    // Функция обновления графика транзакций с сервера
    function updateTransactionsChart(data) {
        try {
            console.log("Обновляем график транзакций с новыми данными:", data);

            if (!data || !data.transactions) {
                console.error("Отсутствуют данные для графика транзакций");
                return;
            }

            // Обновляем информацию о транзакциях
            $('#transactions-legend .chart-legend__total span').text(data.transactions.count || 0);
            $('#transactions-legend .chart-legend__item div:last-child').text((data.transactions.sum || 0) +
                ' сом');

            // Если график уже создан и есть данные для графика, обновляем его
            if (data.transactions.labels && data.transactions.values) {
                // Если график не существует или не имеет методов обновления, пересоздаем его
                if (!window.transactionsChart || typeof window.transactionsChart.update !== 'function') {
                    console.log("График не инициализирован или не имеет метода update, пересоздаем...");

                    // Сохраняем новые данные для использования при создании графика
                    transactionsChartData = {
                        labels: data.transactions.labels,
                        data: data.transactions.values
                    };

                    // Пересоздаем график
                    createTransactionsChart();
                } else {
                    // Обновляем данные существующего графика
                    console.log("Обновляем данные существующего графика");
                    window.transactionsChart.data.labels = data.transactions.labels;
                    window.transactionsChart.data.datasets[0].data = data.transactions.values;
                    window.transactionsChart.update();
                }

                // Добавляем анимацию обновления
                const chartBlock = $('.transactions-chart');
                if (chartBlock.length) {
                    chartBlock.addClass('updated');
                    setTimeout(function() {
                        chartBlock.removeClass('updated');
                    }, 1000);
                }
            } else {
                console.error("Отсутствуют необходимые данные для графика (labels или values)");
            }
        } catch (error) {
            console.error('Ошибка при обновлении графика транзакций:', error);
        }
    }

    // Вызываем инициализацию графика при загрузке страницы
    initTransactionsChart();
});
</script>
@endpush