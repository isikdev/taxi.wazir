@extends('disp.layout')
@section('title', 'Главная панель - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
/* Стили для статусов заказов */
.status-new {
    color: #0066cc;
    font-weight: bold;
}

.status-in_progress {
    color: #ff9900;
    font-weight: bold;
}

.status-completed {
    color: #4CAF50;
    font-weight: bold;
}

.status-cancelled {
    color: #f44336;
    font-weight: bold;
}

/* Стили для карты */
.map-container {
    width: 100%;
    height: 500px;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    border: 2px solid #444;
    margin-top: 20px;
}

#map {
    width: 100%;
    height: 100%;
}

/* Стили для пагинации */
.main__table-pagination-item button {
    cursor: pointer;
}

.main__table-pagination-active button {
    color: white;
}

.main__table-pagination-item:not(.main__table-pagination-active) button:hover {
    background-color: #ddd;
}

.main__table-pagination-prev button,
.main__table-pagination-next button {
    cursor: pointer;
}

.disabled {
    opacity: 0.5;
    cursor: not-allowed !important;
}
</style>
@endpush

@section('content')
<!-- Таблица заказов -->
<div class="main__table main__paybalance-table">
    <table>
        <thead>
            <tr>
                <th>№ заказа</th>
                <th>Статус</th>
                <th>Дата/Время</th>
                <th>Клиент</th>
                <th>Откуда</th>
                <th>Куда</th>
                <th>Цена</th>
                <th>Водитель</th>
            </tr>
        </thead>
        <tbody id="orders-table-body">
            <!-- Данные о заказах будут загружены через AJAX -->
        </tbody>
    </table>
    <!-- <div class="main__table-footer">
        <div class="main__table-pagination" id="orders-pagination-container">
        </div>
    </div> -->
</div>

<!-- Карта водителей -->
<div class="map-container">
    <div id="map"></div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<!-- Подключаем Google Maps API -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDVo7wbikITN-adAlS0AAdQFb9u7uozmUE&libraries=places&callback=initMap"
    async defer></script>

<script>
// Глобальные переменные для отслеживания состояния заказов
let ordersCurrentPage = 1;
let ordersTotalPages = 1;
let ordersFilters = {
    status: '',
    search: '',
    per_page: 10
};

// Глобальные переменные для карты
let map;
let markers = {};

// Функция инициализации Google Maps
function initMap() {
    // Координаты центра Киргизии (Бишкек)
    const bishkek = {
        lat: 42.8746,
        lng: 74.5698
    };

    // Создаем карту
    map = new google.maps.Map(document.getElementById('map'), {
        center: bishkek,
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

    // Загружаем водителей и обновляем их на карте
    loadDriversForMap();

    // Обновляем позиции водителей каждые 5 секунд
    setInterval(loadDriversForMap, 5000);
}

// Функция для загрузки данных о водителях для карты
function loadDriversForMap() {
    fetch('/backend/disp/drivers/json')
        .then(response => response.json())
        .then(response => {
            updateDriversOnMap(response);
        })
        .catch(error => console.error('Ошибка при загрузке данных о водителях:', error));
}

// Функция для обновления позиций водителей на карте
function updateDriversOnMap(drivers) {
    // Сохраняем список текущих маркеров
    const currentMarkers = {
        ...markers
    };

    // Обновляем маркеры для каждого водителя
    drivers.forEach(driver => {
        // ID водителя
        const id = driver.id;

        // Если у водителя есть координаты
        if (driver.lat && driver.lng) {
            const position = {
                lat: parseFloat(driver.lat),
                lng: parseFloat(driver.lng)
            };

            // Если маркер для этого водителя уже существует - обновляем его
            if (markers[id]) {
                markers[id].setPosition(position);
                markers[id].setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: getDriverStatusColor(driver.status),
                    fillOpacity: 0.8,
                    strokeWeight: 2,
                    strokeColor: '#ffffff',
                    scale: 15
                });
            }
            // Иначе - создаем новый маркер
            else {
                markers[id] = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: getDriverStatusColor(driver.status),
                        fillOpacity: 0.8,
                        strokeWeight: 2,
                        strokeColor: '#ffffff',
                        scale: 15
                    },
                    title: driver.full_name || 'Водитель ' + id
                });

                // Добавляем обработчик клика на маркер для показа информации о водителе
                google.maps.event.addListener(markers[id], 'click', function() {
                    showDriverInfo(driver, markers[id]);
                });
            }

            // Удаляем ID из списка текущих маркеров
            delete currentMarkers[id];
        }
    });

    // Удаляем маркеры, которые больше не нужны
    for (const id in currentMarkers) {
        currentMarkers[id].setMap(null);
        delete markers[id];
    }
}

// Функция для определения цвета маркера в зависимости от статуса водителя
function getDriverStatusColor(status) {
    switch (status) {
        case 'free':
            return 'green';
        case 'busy':
            return 'red';
        case 'online':
            return 'green';
        default:
            return 'gray';
    }
}

// Функция для показа информации о водителе при клике на маркер
function showDriverInfo(driver, marker) {
    // Создаем информационное окно
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="color: black; padding: 10px;">
                <h3 style="margin-top: 0;">${driver.full_name || 'Водитель ' + driver.id}</h3>
                <p><strong>Телефон:</strong> ${driver.phone || 'Не указан'}</p>
                <p><strong>Статус:</strong> ${getDriverStatusText(driver.status)}</p>
                <p><strong>Автомобиль:</strong> ${driver.car_brand || ''} ${driver.car_model || 'Не указан'}</p>
                <p><strong>Гос. номер:</strong> ${driver.license_plate || 'Не указан'}</p>
            </div>
        `
    });

    // Показываем информационное окно
    infoWindow.open(map, marker);
}

// Функция для получения текстового представления статуса
function getDriverStatusText(status) {
    switch (status) {
        case 'free':
            return 'Свободен';
        case 'busy':
            return 'Занят';
        case 'online':
            return 'Онлайн';
        case 'offline':
            return 'Офлайн';
        default:
            return 'Неизвестно';
    }
}

// Функция для генерации пагинации
function generatePagination(currentPage, lastPage, containerId, callback) {
    let paginationHtml = '';

    // Кнопка "Предыдущая"
    const prevDisabled = currentPage <= 1 ? 'disabled' : '';
    paginationHtml += `
        <div class="main__table-pagination-prev ${prevDisabled}">
            <button ${prevDisabled} data-page="${currentPage - 1}">
                <img src="{{ asset('assets/img/disp/ico/prev.png') }}" alt="prev">
            </button>
        </div>
    `;

    // Определяем диапазон страниц для отображения
    let startPage = Math.max(1, currentPage - 2);
    let endPage = Math.min(lastPage, startPage + 4);

    if (endPage - startPage < 4 && lastPage > 4) {
        startPage = Math.max(1, endPage - 4);
    }

    // Генерируем кнопки страниц
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'main__table-pagination-active' : '';
        paginationHtml += `
            <div class="main__table-pagination-item ${activeClass}">
                <button data-page="${i}">${i}</button>
            </div>
        `;
    }

    // Кнопка "Следующая"
    const nextDisabled = currentPage >= lastPage ? 'disabled' : '';
    paginationHtml += `
        <div class="main__table-pagination-next ${nextDisabled}">
            <button ${nextDisabled} data-page="${currentPage + 1}">
                <img src="{{ asset('assets/img/disp/ico/next.png') }}" alt="next">
            </button>
        </div>
    `;

    $(`#${containerId}`).html(paginationHtml);

    // Добавляем обработчики событий для кнопок пагинации
    $(`#${containerId} button:not([disabled])`).click(function() {
        const page = $(this).data('page');
        if (page) {
            callback(page);
        }
    });
}

// Функция для получения и отображения заказов
function updateOrders() {
    // Объединяем текущие фильтры с номером страницы
    const params = {
        ...ordersFilters,
        page: ordersCurrentPage
    };

    // Отправляем запрос к API для получения заказов
    $.ajax({
        url: "/backend/disp/orders/json",
        method: "GET",
        data: params,
        success: function(response) {
            if (response && (response.success || Array.isArray(response) || response.data)) {
                // Если получили данные, передаем их для отображения
                renderOrdersTable(Array.isArray(response) ? {
                    data: response
                } : response.data || response);

                // Обновляем состояние пагинации
                const responseData = Array.isArray(response) ? {
                    last_page: 1
                } : (response.data || response);
                ordersTotalPages = responseData.last_page || 1;
                generatePagination(ordersCurrentPage, ordersTotalPages, 'orders-pagination-container',
                    function(page) {
                        ordersCurrentPage = page;
                        updateOrders();
                    });
            } else {
                // Если данных нет, показываем сообщение "Заказов пока нет"
                renderEmptyTable();
            }
        },
        error: function(error) {
            console.error("Ошибка при загрузке заказов:", error);
            // В случае ошибки тоже показываем сообщение об отсутствии заказов
            renderEmptyTable();
        }
    });
}

// Функция для отображения данных заказов в таблице
function renderOrdersTable(response) {
    const orders = response.data || [];
    let html = '';

    if (orders.length === 0) {
        html =
            '<tr><td colspan="8" class="text-center" style="padding: 20px; font-size: 16px; color: #999;">Заказов пока нет</td></tr>';
    } else {
        orders.forEach(order => {
            let statusClass = '';
            let statusText = 'Неизвестно';

            switch (order.status) {
                case 'new':
                    statusClass = 'status-new';
                    statusText = 'Новый';
                    break;
                case 'in_progress':
                    statusClass = 'status-in_progress';
                    statusText = 'В процессе';
                    break;
                case 'completed':
                    statusClass = 'status-completed';
                    statusText = 'Завершен';
                    break;
                case 'cancelled':
                    statusClass = 'status-cancelled';
                    statusText = 'Отменен';
                    break;
            }

            // Форматируем дату и время
            let dateTimeStr = '';
            if (order.date) {
                const date = new Date(order.date);
                dateTimeStr = date.toLocaleDateString('ru-RU');

                if (order.time) {
                    const time = new Date(order.time);
                    dateTimeStr += ' ' + time.toLocaleTimeString('ru-RU', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }

            html += `
                <tr>
                    <td>${order.order_number || '-'}</td>
                    <td class="${statusClass}">${statusText}</td>
                    <td>${dateTimeStr || '-'}</td>
                    <td>${order.client_name || '-'}</td>
                    <td>${order.origin_street} ${order.origin_house || ''}</td>
                    <td>${order.destination_street} ${order.destination_house || ''}</td>
                    <td>${order.price ? order.price + ' ₸' : '-'}</td>
                    <td>${order.driver ? order.driver.full_name : '-'}</td>
                </tr>
            `;
        });
    }

    $('#orders-table-body').html(html);
}

// Функция для отображения пустой таблицы с сообщением
function renderEmptyTable() {
    const html =
        '<tr><td colspan="8" class="text-center" style="padding: 20px; font-size: 16px; color: #999;">Заказов пока нет</td></tr>';
    $('#orders-table-body').html(html);
}

// Инициализация при загрузке страницы
$(document).ready(function() {
    // Загружаем список заказов
    updateOrders();

    // Автоматическое обновление данных каждые 30 секунд
    setInterval(function() {
        updateOrders();
    }, 30000);
});
</script>
@endpush