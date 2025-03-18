@extends('disp.layout')
@section('title', 'Новый заказ - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<!-- Подключаем стили для Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
.main__subheader-add {
    width: 100%;
}

.address-input {
    padding: 10px 12px !important;
    font-size: 16px !important;
}

.main__subheader {
    display: none;
}

.main__order-map-settings-item .main__btn-short {
    font-size: 16px;
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__order-wrapper {
    padding: 20px 0;
    border-radius: 10px;
}

/* Основные блоки страницы */
.main__order-wrapper-blocks {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    min-height: 500px;
    /* Минимальная высота для блоков */
}

/* Блок с настройками заказа */
.main__order-settings {
    max-width: 100% !important;
    width: 100%;
    display: flex;
    flex-direction: column;
    background-color: #2c2c2c;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Блок с картой */
.main__order-map {
    width: 40%;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background-color: #2c2c2c;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Заголовок заказа */
.main__order-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 15px;
    gap: 10px;
    width: 100%;
}

.main__order-header-item {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    flex: 1;
    min-width: 200px;
}

.main__order-header-item p {
    font-weight: bold;
    margin: 0;
    color: #aaa;
    min-width: 80px;
}

/* Подзаголовок заказа (водитель и тарифы) */
.main__order-subheader {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    gap: 15px;
    width: 100%;
}

.main__order-subheader-item {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    width: 100%;
    border-radius: 10px;
}

.main__order-subheader-item:first-child {
    border-bottom: 1px solid #444;
}

/* Выпадающие списки и кнопки */
.main__subheader-filing {
    flex: 1;
    min-width: 200px;
}

.main__order-subheader-item:first-child .main__subheader-filing {
    max-width: 300px;
}

.main__subheader-filing select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
}

.main__btn-short {
    padding: 7px 12px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    cursor: pointer;
    flex: 1;
    min-width: 100px;
    max-width: fit-content;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.main__btn-short:hover {
    background-color: #4a4a4a;
}

/* Стиль для выпадающего списка водителей */
#driver-select {
    width: 100%;
    max-width: 100%;
}

/* Детали заказа (адреса) */
.main__order-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 15px;
    width: 100%;
}

.main__order-details-item {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    width: 100%;
}

.main__order-details-item button {
    flex: 1;
    min-width: 150px;
    text-align: left;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
}

.main__order-details-item button span {
    color: #aaa;
    margin-right: 5px;
}

.main__order-details-item button:hover {
    background-color: #4a4a4a;
}

/* Поле ввода адреса */
.address-input {
    flex: 1;
    min-width: 150px;
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    font-size: 14px;
}

.address-input:focus {
    outline: none;
    border-color: #777;
    background-color: #444;
}

/* Примечания */
.main__order-notes {
    width: 100%;
    margin-bottom: 15px;
}

.main__order-notes-text textarea {
    width: 100%;
    min-height: 80px;
    padding: 10px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    resize: vertical;
}

/* Таблица заказов */
.main__table {
    width: 100%;
    overflow-x: auto;
    margin-top: auto;
    /* Прижимаем таблицу к низу блока */
}

.main__table table {
    width: 100%;
    border-collapse: collapse;
}

.main__table th,
.main__table td {
    padding: 8px 10px;
    text-align: left;
    border-bottom: 1px solid #555;
}

.main__table th {
    background-color: #333;
    color: #ddd;
}

.main__table tr:hover {
    background-color: #3a3a3a;
}

/* Карта */
.main__order-map-item {
    width: 100%;
    border-radius: 8px;
    position: relative;
    height: 350px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border: 2px solid #444;
}

#map {
    width: 100%;
    height: 100%;
    border-radius: 5px;
}

/* Данные для расчета */
.main__order-map-settings {
    margin-top: 10px;
}

.main__order-map-settings button.main__btn {
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
    background-color: #444;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.main__order-map-settings-item {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: space-between;
}

.main__order-map-settings-item .main__btn-short {
    flex: 1;
    min-width: 300px;
    text-align: center;
}

/* Кнопки заказа */
.main__order-wrapper-btn {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.main__btn-green {
    padding: 12px 30px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    letter-spacing: 1px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.3s;
}

.main__btn-green:hover {
    background-color: #3e8e41;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.main__btn {
    padding: 12px 25px;
    background-color: #555;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.main__btn:hover {
    background-color: #666;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Адаптивные стили */
@media (max-width: 1200px) {
    .main__order-wrapper-blocks {
        flex-direction: column;
    }

    .main__order-settings,
    .main__order-map {
        width: 100%;
    }

    .main__order-map {
        order: -1;
        /* Показываем карту выше на мобильных устройствах */
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {

    .main__order-header-item,
    .main__order-subheader-item {
        min-width: 100%;
    }

    .main__btn-short {
        min-width: 80px;
    }

    .main__order-details-item button {
        min-width: 100%;
    }
}

/* Стиль для сообщения на карте */
.map-api-message {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 20px;
    z-index: 999;
    font-size: 16px;
    line-height: 1.5;
}

.map-api-message a {
    color: #4CAF50;
    text-decoration: underline;
    margin-top: 10px;
}

/* Стили для выпадающего списка водителей */
#driver-select,
.tariff-select,
.payment-select {
    width: 100%;
    max-width: 100%;
    padding: 8px 10px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
    background-repeat: no-repeat;
    background-position: right 8px center;
    padding-right: 30px;
}

#driver-select:hover,
.tariff-select:hover,
.payment-select:hover {
    background-color: #444;
    border-color: #777;
}

.main__order-subheader-item {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    flex: 1;
    min-width: 48%;
}

/* Кнопки адресов */
.main__order-details-item button {
    flex: 1;
    min-width: 150px;
    text-align: left;
    padding: 10px 12px;
    border-radius: 5px;
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    transition: background-color 0.2s;
}

.main__order-details-item button:hover {
    background-color: #4a4a4a;
    border-color: #777;
}

/* Улучшенные стили для таблицы */
.main__table {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
    border-radius: 5px;
    background-color: #333;
}

.main__table table {
    width: 100%;
    border-collapse: collapse;
}

.main__table th {
    background-color: #444;
    color: #ddd;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    border: none;
}

.main__table td {
    padding: 10px 15px;
    border-bottom: 1px solid #444;
    color: #eee;
}

.main__table tbody tr:hover {
    background-color: #383838;
}

.main__table tbody tr:last-child td {
    border-bottom: none;
}

/* Кнопки с данными заказа */
.data-btn {
    background-color: #444;
    color: white;
    border: none;
    text-align: center;
    padding: 10px 15px;
    flex: 1;
    min-width: calc(20% - 8px);
    max-width: none;
    font-size: 13px;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.data-btn:hover {
    background-color: #555;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Сглаживание углов таблицы */
.main__table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Стили для кнопок в блоке информации о водителе */
.main__order-subheader-item:first-child .main__btn-short {
    min-width: 180px;
    padding: 10px 15px;
    text-align: left;
    font-size: 14px;
    display: flex;
    align-items: center;
    border-radius: 6px;
    transition: all 0.3s ease;
    border: none;
}

#driver-phone {
    color: white;
    position: relative;
    padding-left: 38px;
    font-size: 16px;
}

#driver-phone::before {
    content: '\260E';
    /* Unicode телефонного символа */
    position: absolute;
    left: 12px;
    font-size: 16px;
}

#driver-phone:hover {
    background-color: #1E4EC7;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

#driver-car {
    color: white;
    position: relative;
    padding-left: 38px;
    font-size: 16px;
}

#driver-car::before {
    content: '\1F697';
    /* Unicode символа автомобиля */
    position: absolute;
    left: 12px;
    font-size: 16px;
}

#driver-car:hover {
    background-color: #4527A0;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Стиль для селекта водителя */
#driver-select {
    height: 42px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
}

/* Стили для второго блока (тип и оплата) */
.main__order-subheader-item:last-child {
    padding-top: 5px;
    justify-content: space-between;
}

.main__order-subheader-item:last-child .main__subheader-filing {
    flex: 1;
    max-width: 48%;
}

/* Стили для заголовков секций */
.subheader-title {
    width: 100%;
    margin: 0 0 10px 0;
    padding: 0 0 8px 0;
    font-size: 16px;
    font-weight: 600;
    color: #e0e0e0;
    border-bottom: 1px solid #444;
    position: relative;
}

.subheader-title::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #4CAF50, #2196F3);
    border-radius: 3px;
}

/* Контейнеры для информации */
.driver-info-container,
.order-params-container {
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
}

/* Дополнительные стили для блоков с информацией */
.main__order-subheader-item {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    padding: 15px;
    background-color: #333;
}

.main__order-wrapper {
    background: transparent;
}

/* Добавляем только нужные стили для работы с Google Maps */
.pac-container {
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    margin-top: 5px;
    font-family: inherit;
}

.pac-item {
    padding: 8px;
    font-size: 14px;
}

.pac-item:hover {
    background-color: #333;
}

.text-center {
    text-align: center;
}

.orders-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.refresh-btn {
    background-color: #3a3a3a;
    color: white;
    border: 1px solid #555;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    background-color: #4a4a4a;
    transform: rotate(180deg);
}

.refresh-btn.rotating {
    animation: rotate 1s linear;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}
</style>
@endpush
@section('content')
<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__header-search-item">
            <button class="main__btn">+ Новый (F2)</button>
        </div>
    </div>
    <div class="main__subheader-drivers">
        <div class="main__header-tags main__subheader-drivers-tags">
            <ul>
                <li>На линии 0 водителей</li>
                <li><span class="status-span free"></span> 0 свободный</li>
                <li><span class="status-span busy"></span> 0 занят</li>
            </ul>
        </div>
        <div class="main__subheader-balance">
            <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
            <p>Баланс: 10,000</p>
        </div>
    </div>
</div>
<div class="main__order-wrapper">
    <div class="main__order-wrapper-blocks">
        <div class="main__order-settings">
            <div class="main__order-header">
                <div class="main__order-header-item">
                    <p>Заказ №</p>
                    <button class="main__btn-short" id="order-number">ORD00000000</button>
                </div>
                <div class="main__order-header-item">
                    <p>Дата время</p>
                    <button class="main__btn-short" id="current-date">16.07.24</button>
                    <button class="main__btn-short" id="current-time">21:50</button>
                </div>
                <div class="main__order-header-item">
                    <p>Путевой лист</p>
                    <button class="main__btn-short">12345678</button>
                </div>
            </div>
            <div class="main__order-subheader">
                <div class="main__order-subheader-item">
                    <h3 class="subheader-title">Информация о водителе</h3>
                    <div class="driver-info-container">
                        <div class="main__subheader-filing">
                            <form action="#">
                                <select name="driver-select" id="driver-select">
                                    <option value="" disabled selected>Выберите водителя</option>
                                </select>
                            </form>
                        </div>
                        <button class="main__btn-short" id="driver-phone">+996 (xxx)xxxxxx</button>
                        <button class="main__btn-short" id="driver-car">Авто: -</button>
                    </div>
                </div>
                <div class="main__order-subheader-item">
                    <h3 class="subheader-title">Параметры заказа</h3>
                    <div class="order-params-container">
                        <div class="main__subheader-filing">
                            <form action="#">
                                <select name="filing-date" id="tariff-select" class="tariff-select">
                                    <option value="Вариант" disabled selected>Вариант</option>
                                </select>
                            </form>
                        </div>
                        <div class="main__subheader-filing">
                            <form action="#">
                                <select name="filing-date" id="payment-method-select" class="payment-select">
                                    <option value="Оплата" disabled selected>Оплата</option>
                                    <option value="Наличные">Наличные</option>
                                    <option value="Приложение">Приложение</option>
                                    <option value="Картой">Картой</option>
                                    <option value="Корпоративный">Корпоративный</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main__order-details">
                <div class="main__order-details-item main__order-details-where">
                    <input type="text" class="address-input" id="origin-street" placeholder="Введите адрес отправления">
                    <button class="main__btn" id="origin-house"><span>д.</span></button>
                    <button class="main__btn" id="origin-district"><span>р-н.</span></button>
                </div>
                <div class="main__order-details-item main__order-details-whither">
                    <input type="text" class="address-input" id="destination-street"
                        placeholder="Введите адрес назначения">
                    <button class="main__btn" id="destination-house"><span>д.</span></button>
                    <button class="main__btn" id="destination-district"><span>р-н.</span></button>
                </div>
            </div>
            <div class="main__order-notes">
                <div class="main__order-notes-text">
                    <form>
                        <textarea id="subheader__input-item" placeholder="Примечание"></textarea>
                    </form>
                </div>
            </div>
            <div class="main__table">
                <div class="orders-table-header">
                    <h3 class="subheader-title">История заказов</h3>
                    <button id="refresh-orders-btn" class="refresh-btn" title="Обновить историю заказов">⟳</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Заказ</th>
                            <th>Время</th>
                            <th>Откуда</th>
                            <th>Куда</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        <tr>
                            <td colspan="5" class="text-center">Загрузка данных...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="main__order-map">
            <div class="main__order-map-item">
                <div id="map"></div>
            </div>
            <div class="main__order-map-settings">
                <div class="main__order-map-settings">
                    <button class="main__btn">Данные для рассчета</button>
                </div>
                <div class="main__order-map-settings-item">
                    <button class="main__btn-short data-btn" id="tariff-btn">Эконом</button>
                    <button class="main__btn-short data-btn" id="landing-fee-btn">Посадка: 50 сом</button>
                    <button class="main__btn-short data-btn" id="route-distance">Расстояние: -</button>
                    <button class="main__btn-short data-btn" id="route-duration">Время: -</button>
                    <button class="main__btn-short data-btn" id="route-price">Стоимость: -</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main__order-wrapper-btn">
        <button class="main__btn-green">ЗАКАЗАТЬ</button>
        <button class="main__btn">Отменить</button>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

<!-- Подключаем Google Maps API -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVo7wbikITN-adAlS0AAdQFb9u7uozmUE&libraries=places&callback=initGoogleMap"
    async defer></script>

<script>
// Настройки тарифов - будут заполнены динамически при загрузке данных
let tariffs = {
    'Эконом': {
        basePrice: 50,
        pricePerKm: 70
    }
};

// Текущий выбранный тариф
let currentTariff = 'Эконом';

// Глобальные переменные для маркеров и карты
let map, originMarker, destinationMarker, directionsService, directionsRenderer, geocoder;

// Обработчик выбора тарифа
$(document).ready(function() {
    // Генерируем уникальный номер заказа
    const orderNumber = 'ORD' + new Date().getTime();
    $('#order-number').text(orderNumber);

    // Устанавливаем текущую дату и время
    updateRealTimeDateTime();

    // Запускаем обновление времени каждую секунду
    setInterval(updateRealTimeDateTime, 1000);

    $('#tariff-select').on('change', function() {
        const selectedTariff = $(this).val();
        if (selectedTariff !== 'Вариант') {
            currentTariff = selectedTariff;
            $('#tariff-btn').text(selectedTariff);

            // Обновляем стоимость посадки
            const basePrice = tariffs[selectedTariff] ? tariffs[selectedTariff].basePrice : 50;
            $('#landing-fee-btn').text('Посадка: ' + basePrice + ' сом');

            // Если есть маршрут, пересчитываем стоимость
            if (directionsRenderer && directionsRenderer.getDirections()) {
                updateRouteInfo(directionsRenderer.getDirections());
            }
        }
    });

    // Обработчики для кнопок адресов
    $('.main__order-details-where button').click(function() {
        const streetBtn = $('.main__order-details-where button:nth-child(1)');
        const houseBtn = $('.main__order-details-where button:nth-child(2)');
        const districtBtn = $('.main__order-details-where button:nth-child(3)');

        const addressText = streetBtn.text().replace('ул.', '') + ' ' +
            houseBtn.text().replace('д.', '') + ', ' +
            districtBtn.text().replace('р-н.', '') + ', Бишкек, Киргизия';
        searchAddress(addressText, true);
    });

    $('.main__order-details-whither button').click(function() {
        const streetBtn = $('.main__order-details-whither button:nth-child(1)');
        const houseBtn = $('.main__order-details-whither button:nth-child(2)');
        const districtBtn = $('.main__order-details-whither button:nth-child(3)');

        const addressText = streetBtn.text().replace('ул.', '') + ' ' +
            houseBtn.text().replace('д.', '') + ', ' +
            districtBtn.text().replace('р-н.', '') + ', Бишкек, Киргизия';
        searchAddress(addressText, false);
    });

    // Собираем все данные формы при нажатии кнопки "Заказать"
    $('.main__btn-green').on('click', function() {
        saveOrder();
    });

    // Обработчик для кнопки обновления истории заказов
    $('#refresh-orders-btn').on('click', function() {
        // Получаем ID текущего выбранного водителя (если есть)
        const driverId = $('#driver-select').val();
        // Загружаем историю заказов
        loadOrdersHistory(driverId);

        // Визуальный эффект вращения кнопки
        $(this).addClass('rotating');
        setTimeout(() => {
            $(this).removeClass('rotating');
        }, 1000);
    });
});

// Обновляем номер заказа и текущую дату/время при загрузке страницы
$(document).ready(function() {
    // Устанавливаем номер заказа
    @if(isset($orderNumber))
    $('.main__order-header-item:nth-child(1) .main__btn-short').text('{{ $orderNumber }}');
    @else
    $('.main__order-header-item:nth-child(1) .main__btn-short').text('ORD' + Math.floor(Math.random() *
        1000000000000));
    @endif

    // Получаем и устанавливаем текущую дату и время для Киргизии (UTC+6)
    @if(isset($currentDate))
    $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(2)').text('{{ $currentDate }}');
    @else
    const kyrgyzDateTime = getKyrgyzDateTime();
    $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(2)').text(kyrgyzDateTime.date);
    @endif

    // Устанавливаем текущее время
    @if(isset($currentTime))
    $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(3)').text('{{ $currentTime }}');
    @else
    $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(3)').text(kyrgyzDateTime.time);
    @endif

    // Генерируем путевой лист
    @if(isset($waybillNumber))
    $('.main__order-header-item:nth-child(3) .main__btn-short').text('{{ $waybillNumber }}');
    @else
    const waybillNumber = Math.floor(10000000 + Math.random() * 90000000);
    $('.main__order-header-item:nth-child(3) .main__btn-short').text(waybillNumber.toString());
    @endif

    // Загружаем список водителей
    loadDrivers();

    // Загружаем доступные тарифы
    loadTariffs();

    // Загружаем историю заказов
    loadOrdersHistory();

    // Обработчик выбора водителя
    $('#driver-select').on('change', function() {
        const driverId = $(this).val();
        if (driverId) {
            updateDriverInfo(driverId);

            // Загружаем историю заказов для выбранного водителя
            loadOrdersHistory(driverId);
        }
    });

    // Обработчик клика по кнопкам дома и района для адреса отправления
    $('#origin-house, #origin-district').click(function() {
        const buttonId = $(this).attr('id');
        const buttonText = $(this).text().replace(buttonId === 'origin-house' ? 'д.' : 'р-н.', '')
            .trim();
        const newValue = prompt('Введите ' + (buttonId === 'origin-house' ? 'номер дома' : 'район'),
            buttonText);

        if (newValue !== null) {
            $(this).html('<span>' + (buttonId === 'origin-house' ? 'д.' : 'р-н.') + '</span>' +
                newValue);

            // Если есть маршрут, обновляем его
            if (originMarker && destinationMarker) {
                calculateAndDisplayRoute();
            }
        }
    });

    // Обработчик клика по кнопкам дома и района для адреса назначения
    $('#destination-house, #destination-district').click(function() {
        const buttonId = $(this).attr('id');
        const buttonText = $(this).text().replace(buttonId === 'destination-house' ? 'д.' : 'р-н.', '')
            .trim();
        const newValue = prompt('Введите ' + (buttonId === 'destination-house' ? 'номер дома' :
            'район'), buttonText);

        if (newValue !== null) {
            $(this).html('<span>' + (buttonId === 'destination-house' ? 'д.' : 'р-н.') + '</span>' +
                newValue);

            // Если есть маршрут, обновляем его
            if (originMarker && destinationMarker) {
                calculateAndDisplayRoute();
            }
        }
    });

    // Обработчик для кнопки "Заказать"
    $('.main__btn-green').on('click', function() {
        saveOrder();
    });
});

// Функция для получения текущих даты и времени в формате Киргизии (UTC+6)
function getKyrgyzDateTime() {
    // Создаем объект Date с текущей датой/временем
    const now = new Date();

    // Получаем смещение времени для Киргизии (UTC+6)
    const kyrgyzOffsetHours = 6;

    // Вычисляем смещение в миллисекундах для киргизского времени
    const localOffsetMs = now.getTimezoneOffset() * 60 * 1000; // локальное смещение в мс от UTC
    const kyrgyzOffsetMs = kyrgyzOffsetHours * 60 * 60 * 1000; // смещение Киргизии в мс

    // Создаем новую дату с учетом смещения для Киргизии
    const kyrgyzDate = new Date(now.getTime() + localOffsetMs + kyrgyzOffsetMs);

    // Форматируем дату в киргизском формате: ДД.ММ.ГГ
    const day = kyrgyzDate.getDate().toString().padStart(2, '0');
    const month = (kyrgyzDate.getMonth() + 1).toString().padStart(2, '0');
    const year = kyrgyzDate.getFullYear().toString().substr(-2);
    const formattedDate = `${day}.${month}.${year}`;

    // Форматируем время в 24-часовом киргизском формате: ЧЧ:ММ:СС
    const hours = kyrgyzDate.getHours().toString().padStart(2, '0');
    const minutes = kyrgyzDate.getMinutes().toString().padStart(2, '0');
    const seconds = kyrgyzDate.getSeconds().toString().padStart(2, '0');
    const formattedTime = `${hours}:${minutes}:${seconds}`;

    return {
        date: formattedDate,
        time: formattedTime,
        dateTime: formattedDate + ' ' + formattedTime,
        dateObject: kyrgyzDate,
        timeNoSeconds: `${hours}:${minutes}`
    };
}

// Функция для загрузки списка водителей
function loadDrivers() {
    $.ajax({
        url: '{{ route("dispatcher.backend.drivers.json") }}',
        method: 'GET',
        success: function(response) {
            const drivers = Array.isArray(response) ? response : (response.data || []);
            populateDriverSelect(drivers);
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при загрузке списка водителей:', error);
            alert('Не удалось загрузить список водителей. Пожалуйста, обновите страницу или обратитесь к администратору.');
        }
    });
}

// Функция для загрузки доступных тарифов
function loadTariffs() {
    $.ajax({
        url: '/backend/disp/tariffs/json',
        method: 'GET',
        success: function(response) {
            const tariffs = Array.isArray(response) ? response : (response.data || []);
            populateTariffSelect(tariffs);
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при загрузке тарифов:', error);

            // Если API еще не реализован, используем стандартные тарифы
            const defaultTariffs = [{
                    id: 1,
                    name: 'Эконом',
                    base_price: 50,
                    price_per_km: 70
                },
                {
                    id: 2,
                    name: 'Люкс',
                    base_price: 100,
                    price_per_km: 70
                },
                {
                    id: 3,
                    name: 'Бизнес',
                    base_price: 150,
                    price_per_km: 70
                }
            ];
            populateTariffSelect(defaultTariffs);
        }
    });
}

// Функция для заполнения выпадающего списка тарифов
function populateTariffSelect(tariffList) {
    const tariffSelect = $('#tariff-select');

    // Очищаем предыдущие опции, сохраняя первую (заголовок)
    const firstOption = tariffSelect.find('option:first').clone();
    tariffSelect.empty().append(firstOption);

    // Добавляем опции тарифов
    if (tariffList && tariffList.length > 0) {
        tariffList.forEach(tariff => {
            tariffSelect.append(`<option value="${tariff.name}" 
                data-base-price="${tariff.base_price || 0}"
                data-price-per-km="${tariff.price_per_km || 0}">
                ${tariff.name}
            </option>`);

            // Добавляем тариф в глобальные настройки тарифов
            window.tariffs = window.tariffs || {};
            window.tariffs[tariff.name] = {
                basePrice: tariff.base_price || 0,
                pricePerKm: tariff.price_per_km || 0
            };
        });
    } else {
        tariffSelect.append('<option value="" disabled>Нет доступных тарифов</option>');
    }
}

// Вспомогательная функция для заполнения выпадающего списка водителей
function populateDriverSelect(drivers) {
    const driverSelect = $('#driver-select');

    // Очищаем предыдущие опции, сохраняя первую (заголовок)
    const firstOption = driverSelect.find('option:first').clone();
    driverSelect.empty().append(firstOption);

    // Добавляем опции водителей
    if (drivers && drivers.length > 0) {
        drivers.forEach(driver => {
            // Получаем информацию об автомобиле
            const carInfo = driver.car_brand && driver.car_model ?
                `${driver.car_brand} ${driver.car_model}` :
                (driver.car_model || 'Нет авто');

            driverSelect.append(`<option value="${driver.id}" 
                data-phone="${driver.phone || ''}" 
                data-car-class="${driver.car_class || ''}"
                data-car-info="${carInfo}"
                data-car-brand="${driver.car_brand || ''}"
                data-car-model="${driver.car_model || ''}"
                data-car-color="${driver.car_color || ''}"
                data-car-year="${driver.car_year || ''}"
                data-license-plate="${driver.license_plate || ''}">
                ${driver.name || driver.full_name || 'Водитель'} (${carInfo})
            </option>`);
        });
    } else {
        driverSelect.append('<option value="" disabled>Нет доступных водителей</option>');
    }
}

// Функция для обновления информации о водителе при его выборе
function updateDriverInfo(driverId) {
    const selectedOption = $(`#driver-select option[value="${driverId}"]`);

    if (selectedOption.length) {
        // Обновляем телефон
        const phone = selectedOption.data('phone');
        $('#driver-phone').text(phone || '+996 (xxx)xxxxxx');

        // Обновляем информацию об автомобиле
        const carInfo = selectedOption.data('car-info') || '-';
        const licensePlate = selectedOption.data('license-plate') || '';

        let carText = 'Авто: ' + carInfo;
        if (licensePlate) {
            carText += ' (' + licensePlate + ')';
        }

        $('#driver-car').text(carText);

        // Обновляем класс автомобиля/тариф
        const carClass = selectedOption.data('car-class');
        if (carClass && $('#tariff-select option[value="' + carClass + '"]').length) {
            $('#tariff-select').val(carClass).trigger('change');
        }
    }
}

// Функция для сбора всех данных заказа
function saveOrder() {
    // Получаем актуальную киргизскую дату и время
    const kyrgyzDateTime = getKyrgyzDateTime();

    // Собираем информацию о заказе
    const orderData = {
        order_number: $('.main__order-header-item:nth-child(1) .main__btn-short').text(),
        date: $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(2)').text() || kyrgyzDateTime
            .date,
        time: $('.main__order-header-item:nth-child(2) .main__btn-short:nth-child(3)').text() || kyrgyzDateTime
            .time,
        waybill: $('.main__order-header-item:nth-child(3) .main__btn-short').text(),
        driver_id: $('#driver-select').val(),
        phone: $('#driver-phone').text(),
        tariff: $('#tariff-select').val(),
        payment_method: $('#payment-method-select').val(),
        origin_street: $('#origin-street').val(),
        origin_house: $('#origin-house').text().replace('д.', '').trim(),
        origin_district: $('#origin-district').text().replace('р-н.', '').trim(),
        destination_street: $('#destination-street').val(),
        destination_house: $('#destination-house').text().replace('д.', '').trim(),
        destination_district: $('#destination-district').text().replace('р-н.', '').trim(),
        notes: $('#subheader__input-item').val(),
        distance: $('#route-distance').text().replace('Расстояние: ', ''),
        duration: $('#route-duration').text().replace('Время: ', ''),
        price: $('#route-price').text().replace('Стоимость: ', '').replace(' сом', ''),
        kyrgyz_datetime: kyrgyzDateTime.dateTime // Добавляем полную дату и время
    };

    // Проверка обязательных полей
    if (!orderData.driver_id) {
        alert('Выберите водителя!');
        return;
    }

    if (!orderData.tariff || orderData.tariff === 'Вариант') {
        alert('Выберите тариф!');
        return;
    }

    if (!orderData.payment_method || orderData.payment_method === 'Оплата') {
        alert('Выберите способ оплаты!');
        return;
    }

    if (!orderData.origin_street) {
        alert('Укажите улицу отправления!');
        return;
    }

    if (!orderData.destination_street) {
        alert('Укажите улицу назначения!');
        return;
    }

    // Если нет цены, предупреждаем
    if (orderData.price === '-') {
        if (!confirm('Стоимость не рассчитана. Продолжить?')) {
            return;
        }
    }

    console.log('Данные заказа:', orderData);

    // AJAX запрос на сохранение данных в БД
    $.ajax({
        url: '/api/orders',
        method: 'POST',
        data: orderData,
        success: function(response) {
            console.log('Ответ сервера:', response);

            if (response.success) {
                alert('Заказ успешно создан!');

                // Очищаем форму или перенаправляем пользователя
                if (confirm('Заказ успешно создан! Создать новый заказ?')) {
                    // Перезагружаем страницу для нового заказа
                    window.location.reload();
                } else {
                    // Перенаправляем на страницу со списком заказов или другую
                    window.location.href = '/backend/disp';
                }
            } else {
                alert('Ошибка: ' + (response.message || 'Не удалось создать заказ'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при создании заказа:', xhr.responseText);

            let errorMessage = 'Не удалось создать заказ';

            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
                if (response.errors) {
                    errorMessage += ': ' + Object.values(response.errors).join(', ');
                }
            } catch (e) {
                console.error('Ошибка при парсинге ответа:', e);
            }

            alert('Ошибка: ' + errorMessage);
        }
    });
}

// Функция инициализации Google Maps
function initGoogleMap() {
    // Координаты центра Киргизии (Бишкек)
    const kyrgyzstan = {
        lat: 42.8746,
        lng: 74.5698
    };

    // Создаем объекты для карты, сервисов и рендерера
    map = new google.maps.Map(document.getElementById('map'), {
        center: kyrgyzstan,
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [{
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#ffffff"
                }]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#000000"
                }, {
                    "lightness": 13
                }]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#000000"
                }]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#144b53"
                }, {
                    "lightness": 14
                }, {
                    "weight": 1.4
                }]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{
                    "color": "#08304b"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#0c4152"
                }, {
                    "lightness": 5
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#000000"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#0b434f"
                }, {
                    "lightness": 25
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry.fill",
                "stylers": [{
                    "color": "#000000"
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#0b3d51"
                }, {
                    "lightness": 16
                }]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#000000"
                }]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{
                    "color": "#146474"
                }]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{
                    "color": "#021019"
                }]
            }
        ]
    });

    // Инициализируем сервисы Google Maps
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true, // Скрываем стандартные маркеры, будем использовать свои
        polylineOptions: {
            strokeColor: '#3388ff',
            strokeWeight: 6,
            strokeOpacity: 0.7
        }
    });
    geocoder = new google.maps.Geocoder();

    // Настраиваем автозаполнение для полей ввода адреса
    setupAutocomplete();

    // Добавляем обработчик клика по карте
    map.addListener('click', function(e) {
        placeMarker(e.latLng);
    });
}

// Настраиваем автозаполнение для полей ввода адреса
function setupAutocomplete() {
    // Настраиваем автозаполнение для адреса отправления
    const originInput = document.getElementById('origin-street');
    const autocompleteOrigin = new google.maps.places.Autocomplete(originInput, {
        componentRestrictions: {
            country: 'kg'
        } // Ограничиваем результаты Киргизией
    });

    // Настраиваем автозаполнение для адреса назначения
    const destinationInput = document.getElementById('destination-street');
    const autocompleteDestination = new google.maps.places.Autocomplete(destinationInput, {
        componentRestrictions: {
            country: 'kg'
        } // Ограничиваем результаты Киргизией
    });

    // Добавляем обработчики для автозаполнения
    autocompleteOrigin.addListener('place_changed', function() {
        const place = autocompleteOrigin.getPlace();
        if (!place.geometry) return;

        // Обновляем адресные данные
        updateAddressComponents(place, true);

        // Устанавливаем маркер на карте
        if (originMarker) originMarker.setMap(null);
        originMarker = new google.maps.Marker({
            position: place.geometry.location,
            map: map,
            draggable: true,
            title: "Место отправления",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });

        // Центрируем карту на месте отправления
        map.setCenter(place.geometry.location);
        map.setZoom(15);

        // Обработчик события перетаскивания маркера
        google.maps.event.addListener(originMarker, 'dragend', function() {
            geocodeLatLng(originMarker.getPosition(), true);
            if (destinationMarker) {
                calculateAndDisplayRoute();
            }
        });

        // Если есть маркер назначения, рисуем маршрут
        if (destinationMarker) {
            calculateAndDisplayRoute();
        }
    });

    autocompleteDestination.addListener('place_changed', function() {
        const place = autocompleteDestination.getPlace();
        if (!place.geometry) return;

        // Обновляем адресные данные
        updateAddressComponents(place, false);

        // Устанавливаем маркер на карте
        if (destinationMarker) destinationMarker.setMap(null);
        destinationMarker = new google.maps.Marker({
            position: place.geometry.location,
            map: map,
            draggable: true,
            title: "Место назначения",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        // Центрируем карту на месте назначения
        map.setCenter(place.geometry.location);
        map.setZoom(15);

        // Обработчик события перетаскивания маркера
        google.maps.event.addListener(destinationMarker, 'dragend', function() {
            geocodeLatLng(destinationMarker.getPosition(), false);
            calculateAndDisplayRoute();
        });

        // Если есть маркер отправления, рисуем маршрут
        if (originMarker) {
            calculateAndDisplayRoute();
        }
    });

    // Добавляем обработчики событий для полей ввода (поиск по нажатию Enter)
    $('#origin-street').keypress(function(e) {
        if (e.which == 13) { // Код клавиши Enter
            e.preventDefault();
            const address = $(this).val();
            searchAddress(address, true);
        }
    });

    $('#destination-street').keypress(function(e) {
        if (e.which == 13) { // Код клавиши Enter
            e.preventDefault();
            const address = $(this).val();
            searchAddress(address, false);
        }
    });
}

// Функция для обновления компонентов адреса из объекта Place
function updateAddressComponents(place, isOrigin) {
    const components = place.address_components;

    let house = '';
    let district = '';

    // Извлекаем компоненты адреса
    for (let i = 0; i < components.length; i++) {
        const component = components[i];
        if (component.types.includes('street_number')) {
            house = component.long_name;
        } else if (component.types.includes('sublocality_level_1') || component.types.includes('sublocality')) {
            district = component.long_name;
        } else if (district === '' && component.types.includes('administrative_area_level_2')) {
            district = component.long_name;
        }
    }

    // Обновляем кнопки с информацией о доме и районе
    if (isOrigin) {
        $('#origin-house').html('<span>д.</span>' + (house || 'Не определен'));
        $('#origin-district').html('<span>р-н.</span>' + (district || 'Не определен'));
    } else {
        $('#destination-house').html('<span>д.</span>' + (house || 'Не определен'));
        $('#destination-district').html('<span>р-н.</span>' + (district || 'Не определен'));
    }
}

// Функция для размещения маркера
function placeMarker(latLng) {
    // Если это первый клик, создаем маркер начальной точки
    if (!originMarker) {
        originMarker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: "Место отправления",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });

        // Получаем адрес по координатам и обновляем кнопки с адресом отправления
        geocodeLatLng(latLng, true);

        // Обработчик события перетаскивания маркера
        google.maps.event.addListener(originMarker, 'dragend', function() {
            geocodeLatLng(originMarker.getPosition(), true);
            if (destinationMarker) {
                calculateAndDisplayRoute();
            }
        });
    }
    // Если это второй клик, создаем маркер конечной точки
    else if (!destinationMarker) {
        destinationMarker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: "Место назначения",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        // Получаем адрес по координатам и обновляем кнопки с адресом назначения
        geocodeLatLng(latLng, false);

        // Обработчик события перетаскивания маркера
        google.maps.event.addListener(destinationMarker, 'dragend', function() {
            geocodeLatLng(destinationMarker.getPosition(), false);
            calculateAndDisplayRoute();
        });

        // Рисуем маршрут между точками
        calculateAndDisplayRoute();
    }
    // Если оба маркера уже созданы, сбрасываем и начинаем заново
    else {
        // Удаляем маркеры и маршрут
        if (originMarker) originMarker.setMap(null);
        if (destinationMarker) destinationMarker.setMap(null);
        if (directionsRenderer) directionsRenderer.setDirections({
            routes: []
        });

        // Создаем новый маркер начальной точки
        originMarker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: "Место отправления",
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });

        // Получаем адрес по координатам и обновляем кнопки с адресом отправления
        geocodeLatLng(latLng, true);

        // Обработчик события перетаскивания маркера
        google.maps.event.addListener(originMarker, 'dragend', function() {
            geocodeLatLng(originMarker.getPosition(), true);
            if (destinationMarker) {
                calculateAndDisplayRoute();
            }
        });

        destinationMarker = null;

        // Сбрасываем значения расстояния, времени и стоимости
        $('#route-distance').text('Расстояние: -');
        $('#route-duration').text('Время: -');
        $('#route-price').text('Стоимость: -');
    }
}

// Функция для обратного геокодирования (получение адреса по координатам)
function geocodeLatLng(latLng, isOrigin) {
    geocoder.geocode({
        'location': latLng
    }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const address = results[0].formatted_address;
            const components = results[0].address_components;

            let street = '';
            let house = '';
            let district = '';

            // Извлекаем компоненты адреса
            for (let i = 0; i < components.length; i++) {
                const component = components[i];
                if (component.types.includes('route')) {
                    street = component.long_name;
                } else if (component.types.includes('street_number')) {
                    house = component.long_name;
                } else if (component.types.includes('sublocality_level_1') || component.types.includes(
                        'sublocality')) {
                    district = component.long_name;
                } else if (district === '' && component.types.includes('administrative_area_level_2')) {
                    district = component.long_name;
                }
            }

            // Обновляем поля адреса
            if (isOrigin) {
                $('#origin-street').val(street || 'Не определена');
                $('#origin-house').html('<span>д.</span>' + (house || 'Не определен'));
                $('#origin-district').html('<span>р-н.</span>' + (district || 'Не определен'));
            } else {
                $('#destination-street').val(street || 'Не определена');
                $('#destination-house').html('<span>д.</span>' + (house || 'Не определен'));
                $('#destination-district').html('<span>р-н.</span>' + (district || 'Не определен'));
            }
        } else {
            console.error('Ошибка при получении адреса:', status);
            // Устанавливаем базовые значения если не удалось получить адрес
            if (isOrigin) {
                $('#origin-street').val('Не определена');
                $('#origin-house').html('<span>д.</span>-');
                $('#origin-district').html('<span>р-н.</span>-');
            } else {
                $('#destination-street').val('Не определена');
                $('#destination-house').html('<span>д.</span>-');
                $('#destination-district').html('<span>р-н.</span>-');
            }
        }
    });
}

// Функция для поиска адреса на карте (прямое геокодирование)
function searchAddress(address, isOrigin) {
    // Добавляем "Кыргызстан" для более точного поиска
    const searchQuery = address + (address.toLowerCase().includes('кыргызстан') ? '' : ', Кыргызстан');

    geocoder.geocode({
        'address': searchQuery
    }, function(results, status) {
        if (status === 'OK' && results[0]) {
            const location = results[0].geometry.location;

            // Центрируем карту
            map.setCenter(location);
            map.setZoom(15);

            if (isOrigin) {
                // Обновляем маркер отправления
                if (originMarker) originMarker.setMap(null);
                originMarker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true,
                    title: "Место отправления",
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                    }
                });

                // Обновляем кнопки адреса получением точного адреса по координатам
                geocodeLatLng(location, true);

                // Обработчик события перетаскивания маркера
                google.maps.event.addListener(originMarker, 'dragend', function() {
                    geocodeLatLng(originMarker.getPosition(), true);
                    if (destinationMarker) {
                        calculateAndDisplayRoute();
                    }
                });

                if (destinationMarker) {
                    calculateAndDisplayRoute();
                }
            } else {
                // Обновляем маркер назначения
                if (destinationMarker) destinationMarker.setMap(null);
                destinationMarker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true,
                    title: "Место назначения",
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
                    }
                });

                // Обновляем кнопки адреса получением точного адреса по координатам
                geocodeLatLng(location, false);

                // Обработчик события перетаскивания маркера
                google.maps.event.addListener(destinationMarker, 'dragend', function() {
                    geocodeLatLng(destinationMarker.getPosition(), false);
                    if (originMarker) {
                        calculateAndDisplayRoute();
                    }
                });

                if (originMarker) {
                    calculateAndDisplayRoute();
                }
            }
        } else {
            console.error('Адрес не найден:', status);
            alert('Не удалось найти указанный адрес. Пожалуйста, уточните запрос.');
        }
    });
}

// Функция для расчета маршрута
function calculateAndDisplayRoute() {
    if (!originMarker || !destinationMarker) return;

    const request = {
        origin: originMarker.getPosition(),
        destination: destinationMarker.getPosition(),
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(response, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
            updateRouteInfo(response);
        } else {
            console.error('Ошибка при построении маршрута:', status);
            alert('Не удалось построить маршрут. Пожалуйста, выберите другие точки.');
        }
    });
}

// Функция для обновления информации о маршруте
function updateRouteInfo(route) {
    // Получаем данные о маршруте
    const legs = route.routes[0].legs;

    // Вычисляем общее расстояние и время
    let totalDistance = 0;
    let totalDuration = 0;

    for (let i = 0; i < legs.length; i++) {
        totalDistance += legs[i].distance.value;
        totalDuration += legs[i].duration.value;
    }

    // Преобразуем в километры и минуты
    const distance = totalDistance / 1000; // м -> км
    const duration = totalDuration / 60; // сек -> мин

    // Форматируем значения
    const distanceText = distance.toFixed(1) + ' км';
    const durationText = Math.round(duration) + ' мин';

    // Обновляем элементы на странице
    $('#route-distance').text('Расстояние: ' + distanceText);
    $('#route-duration').text('Время: ' + durationText);

    // Рассчитываем стоимость
    const basePrice = tariffs[currentTariff] ? tariffs[currentTariff].basePrice : 50;
    const pricePerKm = tariffs[currentTariff] ? tariffs[currentTariff].pricePerKm : 70;
    const estimatedPrice = Math.round(basePrice + (distance * pricePerKm));

    $('#route-price').text('Стоимость: ' + estimatedPrice + ' сом');
}

// Функция для загрузки истории заказов
function loadOrdersHistory(driverId) {
    $.ajax({
        url: '/backend/disp/orders/history',
        method: 'GET',
        data: {
            driver_id: driverId
        },
        success: function(response) {
            const orders = Array.isArray(response) ? response : (response.data || []);
            populateOrdersTable(orders);
        },
        error: function(xhr, status, error) {
            console.error('Ошибка при загрузке истории заказов:', error);

            // Показываем сообщение что заказов пока нет
            const ordersTableBody = $('#orders-table-body');
            ordersTableBody.html(`
                <tr>
                    <td colspan="5" class="text-center">Заказов пока нет</td>
                </tr>
            `);
        }
    });
}

// Функция для заполнения таблицы истории заказов
function populateOrdersTable(orders) {
    const ordersTableBody = $('#orders-table-body');
    ordersTableBody.empty();

    if (orders && orders.length > 0) {
        orders.forEach(order => {
            // Форматируем дату и время для отображения
            let displayDate = order.date || '';
            let displayTime = order.time || '';

            // Если есть datetime, форматируем его для отображения
            if (order.datetime || order.created_at) {
                try {
                    // Пробуем преобразовать datetime или created_at в киргизский формат
                    const dateObj = new Date(order.datetime || order.created_at);
                    if (!isNaN(dateObj)) {
                        // Получаем смещение времени для Киргизии (UTC+6)
                        const kyrgyzOffsetHours = 6;

                        // Вычисляем смещение в миллисекундах для киргизского времени
                        const localOffsetMs = dateObj.getTimezoneOffset() * 60 * 1000;
                        const kyrgyzOffsetMs = kyrgyzOffsetHours * 60 * 60 * 1000;

                        // Создаем дату с киргизским временем
                        const kyrgyzDate = new Date(dateObj.getTime() + localOffsetMs + kyrgyzOffsetMs);

                        // Форматируем дату и время в киргизском формате
                        const day = kyrgyzDate.getDate().toString().padStart(2, '0');
                        const month = (kyrgyzDate.getMonth() + 1).toString().padStart(2, '0');
                        const year = kyrgyzDate.getFullYear().toString().substr(-2);
                        const hours = kyrgyzDate.getHours().toString().padStart(2, '0');
                        const minutes = kyrgyzDate.getMinutes().toString().padStart(2, '0');

                        displayDate = `${day}.${month}.${year}`;
                        displayTime = `${hours}:${minutes}`;
                    }
                } catch (e) {
                    console.error('Ошибка при форматировании даты:', e);
                }
            }

            // Объединяем дату и время для отображения
            const dateTimeDisplay = displayTime ? (displayDate + ' ' + displayTime) : displayDate;

            const row = `
                <tr>
                    <td>${order.order_number}</td>
                    <td>${dateTimeDisplay}</td>
                    <td>${order.origin_street || ''}, ${order.origin_house || ''}, ${order.origin_district || ''}</td>
                    <td>${order.destination_street || ''}, ${order.destination_house || ''}, ${order.destination_district || ''}</td>
                    <td>${order.price} сом</td>
                </tr>
            `;
            ordersTableBody.append(row);
        });
    } else {
        const row = `
            <tr>
                <td colspan="5" class="text-center">Заказов пока нет</td>
            </tr>
        `;
        ordersTableBody.append(row);
    }
}

// Функция для обновления времени в реальном времени
function updateRealTimeDateTime() {
    const datetime = getKyrgyzDateTime();
    $('#current-date').text(datetime.date);
    $('#current-time').text(datetime.time);
}
</script>
@endpush