@extends('disp.layout')
@section('title', 'Карта водителей - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.map-container {
    width: 100%;
    height: calc(100vh - 130px);
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

.map-legend {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background-color: rgba(35, 35, 35, 0.9);
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    color: white;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.legend-color {
    width: 15px;
    height: 15px;
    display: inline-block;
    margin-right: 8px;
    border-radius: 50%;
}

.legend-text {
    font-size: 14px;
}

.free-driver {
    background-color: green;
}

.busy-driver {
    background-color: red;
}

.offline-driver {
    background-color: gray;
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
    margin-bottom: 10px;
}

.main__subheader-drivers-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: unset;
    gap: 20px;
}

.main__header-tags ul {
    display: flex;
    gap: 20px;
}

.main__header-tags li {
    display: flex;
    align-items: center;
}

.status-span {
    width: 12px;
    height: 12px;
    display: inline-block;
    margin-right: 5px;
    border-radius: 50%;
}

.free {
    background-color: green;
}

.busy {
    background-color: red;
}
</style>
@endpush

@section('content')

<!-- Карта водителей -->
<div class="map-container">
    <div id="map"></div>
    <div class="map-legend">
        <h4 style="margin-top: 0; margin-bottom: 10px;">Легенда</h4>
        <div class="legend-item">
            <span class="legend-color free-driver"></span>
            <span class="legend-text">Свободен</span>
        </div>
        <div class="legend-item">
            <span class="legend-color busy-driver"></span>
            <span class="legend-text">Занят</span>
        </div>
        <div class="legend-item">
            <span class="legend-color offline-driver"></span>
            <span class="legend-text">Офлайн</span>
        </div>
    </div>
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
// Глобальные переменные
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

    // Обновляем позиции водителей каждые 5 секунд для более частого обновления
    setInterval(loadDriversForMap, 5000);
}

// Функция для загрузки данных о водителях
function loadDriversForMap() {
    fetch('/backend/disp/drivers/json')
        .then(response => response.json())
        .then(response => {
            updateDriversOnMap(response);
            updateDriverCounters(response);
        })
        .catch(error => console.error('Ошибка при загрузке данных о водителях:', error));
}

// Функция для обновления счетчиков водителей
function updateDriverCounters(drivers) {
    let onlineCount = 0;
    let freeCount = 0;
    let busyCount = 0;

    drivers.forEach(driver => {
        if (driver.status === 'online' || driver.status === 'free' || driver.status === 'busy') {
            onlineCount++;

            if (driver.status === 'free' || driver.status === 'online') {
                freeCount++;
            } else if (driver.status === 'busy') {
                busyCount++;
            }
        }
    });

    $('#online-drivers-count').text(onlineCount);
    $('#free-drivers-count').text(freeCount);
    $('#busy-drivers-count').text(busyCount);

    // Получение общего баланса
    $.ajax({
        url: "{{ route('dispatcher.backend.get_total_balance') }}",
        method: "GET",
        success: function(balanceResponse) {
            if (balanceResponse.success) {
                $('#total-balance').text(balanceResponse.total_balance.toLocaleString());
            }
        }
    });
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
                    scale: 15 // Размер маркера - увеличен для лучшей видимости
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
                        scale: 15 // Размер маркера - увеличен для лучшей видимости
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
</script>
@endpush