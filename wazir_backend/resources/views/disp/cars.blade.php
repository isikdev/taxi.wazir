@extends('disp.layout')
@section('title', 'Автомобили - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__subheader-drivers-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: unset;
    gap: 20px;
}

.default__cars {
    font-size: 20px;
    text-align: center;
    color: #fff;
}

.main__cars-subheader {
    margin: 20px 0;
}

.main__cars-list {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.main__subheader-cars-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.main__subheader-filing select {
    width: 150px;
}

.main__subheader-drivers-tags {
    margin: 0 15px;
}

.main__subheader-drivers-tags ul {
    padding: 15px 20px;
    margin: 0;
    white-space: nowrap;
}

.reset-filters-btn {
    background-color: #47484c;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 14px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.reset-filters-btn:hover {
    background-color: #535357;
}
</style>
@endpush
@section('content')
<div class="main__cars-subheader main__cars-list">
    <div class="main__subheader-filing main__subheader-cars-filter">
        <form action="#">
            <select name="filing-date" id="status-filter">
                <option value="" disabled selected>Статусы</option>
                <option value="Свободен">Свободен</option>
                <option value="Занят">Занят</option>
            </select>
        </form>
        <form action="#">
            <select name="filing-date" id="brand-filter">
                <option value="" disabled selected>Марка</option>
                <!-- Опции будут добавлены динамически -->
            </select>
        </form>
        <form action="#">
            <select name="filing-date" id="model-filter">
                <option value="" disabled selected>Модель</option>
                <!-- Опции будут добавлены динамически -->
            </select>
        </form>
        <form action="#">
            <select name="filing-date" id="color-filter">
                <option value="" disabled selected>Цвет</option>
                <!-- Опции будут добавлены динамически -->
            </select>
        </form>
        <form action="#">
            <select name="filing-date" id="year-filter">
                <option value="" disabled selected>Год выпуска</option>
                <!-- Опции будут добавлены динамически -->
            </select>
        </form>
    </div>

    <div class="main__header-tags main__subheader-drivers-tags">
        <ul>
            <li>На линии 0 водителей</li>
            <li><span class="status-span free"></span> 0 свободный</li>
            <li><span class="status-span busy"></span> 0 занят</li>
        </ul>
    </div>

    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: {{ number_format($totalBalance ?? 0, 0, '.', ',') }}</p>
    </div>
</div>
<div class="main__table-wrapper">
    <div class="main__table">
        <table>
            <thead>
                <tr>
                    <th>Статус</th>
                    <th>Марка</th>
                    <th>Модель</th>
                    <th>Цвет</th>
                    <th>Год</th>
                    <th>Гос.номер</th>
                    <th>VIN</th>
                    <th>Номер кузова</th>
                    <th>СТС</th>
                    <th>Проверен</th>
                </tr>
            </thead>
            <tbody id="cars-table-body">
                <!-- Здесь будут данные о машинах -->
            </tbody>
        </table>
        <div class="main__table-footer">
            <div class="main__table-car">
                <button class="main__btn-short" id="cars-count">Автомобили: 0</button>
            </div>
            <div class="main__table-pagination">
                <div class="main__table-pagination-prev">
                    <button>
                        <img src="{{ asset('assets/img/disp/ico/prev.png') }}" alt="prev">
                    </button>
                </div>
                <div class="main__table-pagination-active main__table-pagination-item">
                    <button>1</button>
                </div>
                <div class="main__table-pagination-next">
                    <button>
                        <img src="{{ asset('assets/img/disp/ico/next.png') }}" alt="next">
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- 
<p class="default__cars">Пока нету зарегистрированных в системе автомобилей</p>
<h4 class="default__cars">Зарегистрированных водителей в системе: 0</h4> 
--}}
@endsection
@push('scripts')




<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let allCars = [];
let filteredCars = [];

function loadCars() {
    $.ajax({
        url: "{{ route('dispatcher.backend.cars.list') }}",
        method: "GET",
        success: function(response) {
            allCars = Array.isArray(response) ? response : (response.data || []);
            filteredCars = [...allCars];

            populateFilters();
            renderCars();
            addResetButton();
            updateStats();
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при получении списка машин:', error);
        }
    });
}

function populateFilters() {
    const brands = new Set();
    const models = new Set();
    const colors = new Set();
    const years = new Set();

    allCars.forEach(car => {
        if (car.car_brand) brands.add(car.car_brand);
        if (car.car_model) models.add(car.car_model);
        if (car.car_color) colors.add(car.car_color);
        if (car.car_year) years.add(car.car_year);
    });

    const brandSelect = $('#brand-filter');
    const modelSelect = $('#model-filter');
    const colorSelect = $('#color-filter');
    const yearSelect = $('#year-filter');

    const brandFirstOption = brandSelect.find('option:first').clone();
    const modelFirstOption = modelSelect.find('option:first').clone();
    const colorFirstOption = colorSelect.find('option:first').clone();
    const yearFirstOption = yearSelect.find('option:first').clone();

    brandSelect.empty().append(brandFirstOption);
    modelSelect.empty().append(modelFirstOption);
    colorSelect.empty().append(colorFirstOption);
    yearSelect.empty().append(yearFirstOption);

    [...brands].sort().forEach(brand => {
        brandSelect.append(`<option value="${brand}">${brand}</option>`);
    });

    [...models].sort().forEach(model => {
        modelSelect.append(`<option value="${model}">${model}</option>`);
    });

    [...colors].sort().forEach(color => {
        colorSelect.append(`<option value="${color}">${color}</option>`);
    });

    [...years].sort((a, b) => b - a).forEach(year => {
        yearSelect.append(`<option value="${year}">${year}</option>`);
    });
}

function applyFilters() {
    const statusFilter = $('#status-filter').val();
    const brandFilter = $('#brand-filter').val();
    const modelFilter = $('#model-filter').val();
    const colorFilter = $('#color-filter').val();
    const yearFilter = $('#year-filter').val();

    filteredCars = allCars.filter(car => {
        return (!statusFilter || car.status === statusFilter) &&
            (!brandFilter || car.car_brand === brandFilter) &&
            (!modelFilter || car.car_model === modelFilter) &&
            (!colorFilter || car.car_color === colorFilter) &&
            (!yearFilter || car.car_year == yearFilter);
    });

    renderCars();

    // Обновляем статистику на странице
    updateStats();
}

function renderCars() {
    let tbody = $('#cars-table-body');
    tbody.empty();

    if (filteredCars.length > 0) {
        filteredCars.forEach(function(car) {
            let row = '<tr>' +
                '<td><span class="status-free">Подтвержден</span></td>' +
                '<td>' + (car.car_brand || '') + '</td>' +
                '<td>' + (car.car_model || '') + '</td>' +
                '<td>' + (car.car_color || '') + '</td>' +
                '<td>' + (car.car_year || '') + '</td>' +
                '<td>' + (car.license_plate || '') + '</td>' +
                '<td>' + (car.vin || '') + '</td>' +
                '<td>' + (car.body_number || '') + '</td>' +
                '<td>' + (car.sts || '') + '</td>' +
                '<td>Да</td>' +
                '</tr>';

            tbody.append(row);
        });
    } else {
        tbody.append(
            '<tr><td colspan="10" class="default__cars">Нет автомобилей, соответствующих выбранным фильтрам</td></tr>'
        );
    }

    $('#cars-count').text('Автомобили: ' + filteredCars.length);
}

function addResetButton() {
    // Удаляем существующую кнопку сброса, если она есть
    $('#reset-filters-btn').remove();

    // Создаем новую кнопку сброса с правильным классом стиля
    $('.main__subheader-cars-filter').append(
        $('<button>', {
            id: 'reset-filters-btn',
            class: 'reset-filters-btn',
            text: 'Сбросить фильтры',
            click: function(event) {
                event.preventDefault();
                $('#status-filter').val('');
                $('#brand-filter').val('');
                $('#model-filter').val('');
                $('#color-filter').val('');
                $('#year-filter').val('');
                filteredCars = [...allCars];
                renderCars();
                updateStats();
            }
        })
    );
}

// Обновляем данные каждые 30 секунд
setInterval(loadCars, 30000);

// Первая загрузка данных
$(document).ready(function() {
    loadCars();

    // Добавляем обработчики изменения значений фильтров
    $(document).on('change', '#status-filter, #brand-filter, #model-filter, #color-filter, #year-filter',
        function() {
            applyFilters();
        });
});

function updateStats() {
    const online = filteredCars.length;
    const free = filteredCars.filter(car => car.status === 'Свободен').length;
    const busy = filteredCars.filter(car => car.status === 'Занят').length;

    $('.main__subheader-drivers-tags ul li:nth-child(1)').text(`На линии ${online} водителей`);
    $('.main__subheader-drivers-tags ul li:nth-child(2)').html(
        `<span class="status-span free"></span> ${free} свободный`);
    $('.main__subheader-drivers-tags ul li:nth-child(3)').html(`<span class="status-span busy"></span> ${busy} занят`);
}
</script>
@endpush