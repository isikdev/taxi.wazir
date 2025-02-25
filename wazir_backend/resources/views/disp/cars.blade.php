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
</style>
@endpush
@section('content')
<div class="main__cars-subheader main__cars-list" style="margin: 20px 0;">
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
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
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
<!-- <p class="default__cars">Пока нету зарегистрированных в системе автомобилей</p>
<h4 class="default__cars">Зарегистрированных водителей в системе: {{ $drivers->count() }}</h4> -->
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Хранилище для всех автомобилей
let allCars = [];
let filteredCars = [];

// Функция для загрузки данных об автомобилях
function loadCars() {
    $.ajax({
        url: "{{ route('dispatcher.backend.cars.list') }}",
        method: "GET",
        success: function(response) {
            allCars = Array.isArray(response) ? response : (response.data || []);
            filteredCars = [...allCars];

            // Заполняем фильтры
            populateFilters();

            // Отображаем данные
            renderCars();

            // Добавляем кнопку сброса фильтров, если её ещё нет
            addResetButton();
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при получении списка машин:', error);
        }
    });
}

// Функция для заполнения селекторов фильтров
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

    // Сохраняем первые опции каждого селекта
    const brandSelect = $('#brand-filter');
    const modelSelect = $('#model-filter');
    const colorSelect = $('#color-filter');
    const yearSelect = $('#year-filter');

    const brandFirstOption = brandSelect.find('option:first').clone();
    const modelFirstOption = modelSelect.find('option:first').clone();
    const colorFirstOption = colorSelect.find('option:first').clone();
    const yearFirstOption = yearSelect.find('option:first').clone();

    // Очищаем и добавляем первую опцию
    brandSelect.empty().append(brandFirstOption);
    modelSelect.empty().append(modelFirstOption);
    colorSelect.empty().append(colorFirstOption);
    yearSelect.empty().append(yearFirstOption);

    // Добавляем отсортированные опции
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

// Функция для применения фильтров
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
}

// Функция для отображения отфильтрованных данных
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
        // Если нет автомобилей после фильтрации, показываем сообщение
        tbody.append(
            '<tr><td colspan="10" class="default__cars">Нет автомобилей, соответствующих выбранным фильтрам</td></tr>'
        );
    }

    // Обновляем счетчик автомобилей
    $('#cars-count').text('Автомобили: ' + filteredCars.length);
}

// События изменения фильтров
function initEvents() {
    $('#status-filter, #brand-filter, #model-filter, #color-filter, #year-filter').off('change').on('change',
        function() {
            // Снимаем атрибут disabled при выборе элемента
            $(this).find('option:first').prop('disabled', true);

            // Применяем фильтры
            applyFilters();
        });
}

// Добавление кнопки сброса фильтров
function addResetButton() {
    // Если кнопка уже существует, не добавляем новую
    if ($('#reset-filters-btn').length > 0) {
        return;
    }

    // Создаем кнопку после всех форм
    $('.main__subheader-filing').append(
        '<button type="button" id="reset-filters-btn" class="main__btn-short" style="margin-left: 10px;">Сбросить фильтры</button>'
    );

    // Привязываем обработчик клика
    $(document).off('click', '#reset-filters-btn').on('click', '#reset-filters-btn', function(e) {
        console.log('Кнопка сброса нажата');
        resetFilters();
    });
}

// Сброс фильтров
function resetFilters() {
    console.log('Начинаем сброс фильтров');

    try {
        // Сбрасываем значения селекторов на первую опцию
        $('#status-filter').val($('#status-filter option:first').val()).find('option:first').prop('disabled', false);
        $('#brand-filter').val($('#brand-filter option:first').val()).find('option:first').prop('disabled', false);
        $('#model-filter').val($('#model-filter option:first').val()).find('option:first').prop('disabled', false);
        $('#color-filter').val($('#color-filter option:first').val()).find('option:first').prop('disabled', false);
        $('#year-filter').val($('#year-filter option:first').val()).find('option:first').prop('disabled', false);

        // Показываем все автомобили
        filteredCars = [...allCars];
        renderCars();

    } catch (error) {
        console.error('Ошибка при сбросе фильтров:', error);
    }
}

// Обновляем данные каждые 30 секунд
setInterval(loadCars, 30000);

// Первая загрузка данных
$(document).ready(function() {
    loadCars();

    // Добавляем обработчик для ручной инициализации
    setTimeout(function() {
        initEvents();
        addResetButton();
    }, 1000);
});
</script>
@endpush