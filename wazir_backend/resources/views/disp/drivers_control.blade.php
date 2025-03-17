@extends('disp.layout')

@section('title', 'Управление водителями')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/disp/css/main.css') }}">
<style>
.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
}

.main__subheader {
    display: none;
}

.default__cars {
    font-size: 20px;
    text-align: center;
    display: flex;
    justify-content: center;
    margin: 0 auto;
    color: #fff;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.modal.show {
    opacity: 1;
}

.modal-content {
    background-color: #47484c;
    margin: 5% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 800px;
    color: #fff;
    transform: translateY(-20px);
    opacity: 0;
    transition: all 0.3s ease-in-out;
}

.modal.show .modal-content {
    transform: translateY(0);
    opacity: 1;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.close:hover,
.close:focus {
    color: #fff;
    text-decoration: none;
}

.modal-title {
    color: #fff;
    font-size: 24px;
    margin-bottom: 20px;
}

.modal-section {
    margin-bottom: 20px;
    border-bottom: 1px solid #555;
    padding-bottom: 15px;
}

.modal-section:last-child {
    border-bottom: none;
}

.modal-section h3 {
    color: #ddd;
    font-size: 18px;
    margin-bottom: 10px;
}

.modal-row {
    display: flex;
    margin-bottom: 8px;
}

.modal-label {
    flex: 0 0 180px;
    color: #aaa;
}

.modal-value {
    color: #fff;
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.rejection-reason {
    width: 100%;
    background-color: #3a3b3f;
    border: 1px solid #555;
    border-radius: 5px;
    color: #fff;
    padding: 10px;
    margin-bottom: 15px;
    resize: vertical;
}

.photo-gallery {
    margin: 15px 0;
    width: 100%;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: thin;
    scrollbar-color: #555 #47484c;
}

.photo-gallery::-webkit-scrollbar {
    height: 8px;
}

.photo-gallery::-webkit-scrollbar-track {
    background: #47484c;
}

.photo-gallery::-webkit-scrollbar-thumb {
    background-color: #555;
    border-radius: 4px;
}

.photo-gallery-container {
    display: inline-flex;
    gap: 10px;
    padding: 5px 0;
}

.photo-item {
    position: relative;
    display: inline-block;
    width: 150px;
    height: 100px;
    border-radius: 5px;
    overflow: hidden;
    transition: transform 0.2s ease;
}

.photo-item:hover {
    transform: scale(1.05);
}

.photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-item .photo-label {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 3px 5px;
    font-size: 10px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.main__card-item {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.main__card-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.main__btn,
.main__btn-green,
.main__btn-short {
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.main__btn:hover,
.main__btn-green:hover,
.main__btn-short:hover {
    transform: translateY(-2px);
}

.main__btn:active,
.main__btn-green:active,
.main__btn-short:active {
    transform: translateY(1px);
}

.modal-image-viewer {
    display: none;
    position: fixed;
    z-index: 1100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-image-viewer.show {
    opacity: 1;
}

.modal-image-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.modal-image {
    max-width: 90%;
    max-height: 80%;
    object-fit: contain;
    border: 2px solid #ccc;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.modal-image-viewer.show .modal-image {
    transform: scale(1);
}

.modal-image-close {
    position: absolute;
    top: 15px;
    right: 25px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.modal-image-title {
    color: #fff;
    font-size: 18px;
    margin-top: 15px;
}

.status-label {
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
}

.status-waiting {
    background-color: #FFD700;
    color: #333;
}

.status-approved {
    background-color: #4CAF50;
    color: white;
}

.status-rejected {
    background-color: #F44336;
    color: white;
}
</style>
@endpush

@section('content')

<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__header-search-item">
            <form action="#" style="background-color: #47484c;">
                <input type="search" placeholder="Поиск" id="searchInput">
                <button style="padding: 0px;">
                    <img src="{{ asset('assets/img/disp/ico/search.png') }}" alt="search">
                </button>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="filing-status" id="statusFilter">
                    <option value="all" selected>Все заявки</option>
                    <option value="submitted">В ожидании</option>
                    <option value="approved">Одобренные</option>
                    <option value="rejected">Отклоненные</option>
                </select>
            </form>
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

<div class="main__subheader-getbalance-title">
    <h3>Запросы</h3>
</div>

<div class="main__cards-wrapper" id="applicationsContainer">
    @if(isset($pendingApplications) && count($pendingApplications) > 0)
    @foreach($pendingApplications as $application)
    <div class="main__card-item" data-status="{{ $application->survey_status }}">
        <img src="{{ asset('assets/img/disp/passport/1.png') }}" alt="driver">
        <button class="main__btn">{{ $application->full_name ?? 'Имя не указано' }}</button>
        <button class="main__btn">Дата регистрации: {{ $application->created_at->format('d.m.Y') }}</button>
        <button class="main__btn">
            Статус:
            @if($application->survey_status == 'submitted')
            <span class="status-label status-waiting">В ожидании</span>
            @elseif($application->survey_status == 'approved')
            <span class="status-label status-approved">Одобрен</span>
            @else
            <span class="status-label status-rejected">Отклонен</span>
            @endif
        </button>
        <button class="main__btn view-application" data-id="{{ $application->id }}">Посмотреть анкету</button>

        @if($application->survey_status == 'submitted')
        <div class="main__card-btn">
            <button class="main__btn-green approve-application" data-id="{{ $application->id }}">Одобрить</button>
            <button class="main__btn-short reject-application" data-id="{{ $application->id }}">Отменить</button>
        </div>
        @endif
    </div>
    @endforeach
    @else
    <p class="default__cars">
        Пока нету активных заявок
    </p>
    @endif
</div>

<div id="applicationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Анкета водителя</h2>

        <div class="modal-section" id="personalInfo">
            <h3>Личная информация</h3>
            <div class="modal-row">
                <div class="modal-label">ФИО:</div>
                <div class="modal-value" id="fullName"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Телефон:</div>
                <div class="modal-value" id="phone"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Дата рождения:</div>
                <div class="modal-value" id="dateOfBirth"></div>
            </div>

            <h4 style="margin-top: 10px; color: #ddd;">Документы пользователя:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="personalDocs">
                </div>
            </div>
        </div>

        <div class="modal-section" id="licenseInfo">
            <h3>Информация о правах</h3>
            <div class="modal-row">
                <div class="modal-label">Номер прав:</div>
                <div class="modal-value" id="licenseNumber"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Дата выдачи:</div>
                <div class="modal-value" id="licenseIssueDate"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Дата окончания:</div>
                <div class="modal-value" id="licenseExpiryDate"></div>
            </div>
        </div>

        <div class="modal-section" id="vehicleInfo">
            <h3>Информация о транспортном средстве</h3>
            <div class="modal-row">
                <div class="modal-label">Марка:</div>
                <div class="modal-value" id="carBrand"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Модель:</div>
                <div class="modal-value" id="carModel"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Цвет:</div>
                <div class="modal-value" id="carColor"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Год выпуска:</div>
                <div class="modal-value" id="carYear"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Гос. номер:</div>
                <div class="modal-value" id="licensePlate"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">VIN:</div>
                <div class="modal-value" id="vin"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Номер кузова:</div>
                <div class="modal-value" id="bodyNumber"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">СТС:</div>
                <div class="modal-value" id="sts"></div>
            </div>

            <h4 style="margin-top: 10px; color: #ddd;">Фотографии автомобиля:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="carPhotos">
                </div>
            </div>

            <h4 style="margin-top: 10px; color: #ddd;">Фотографии салона:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="interiorPhotos">
                </div>
            </div>
        </div>

        <div class="modal-section" id="serviceInfo">
            <h3>Параметры сервиса</h3>
            <div class="modal-row">
                <div class="modal-label">Позывной:</div>
                <div class="modal-value" id="callsign"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Тип сервиса:</div>
                <div class="modal-value" id="serviceType"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Категория:</div>
                <div class="modal-value" id="category"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Тариф:</div>
                <div class="modal-value" id="tariff"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Трансмиссия:</div>
                <div class="modal-value" id="transmission"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Детское кресло:</div>
                <div class="modal-value" id="childSeat"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Бустеры:</div>
                <div class="modal-value" id="boosters"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Фонарь:</div>
                <div class="modal-value" id="flashlight"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Парковочная машина:</div>
                <div class="modal-value" id="parkingCar"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Наклейка:</div>
                <div class="modal-value" id="sticker"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Статус анкеты:</div>
                <div class="modal-value" id="applicationStatus"></div>
            </div>
        </div>

        <!-- Секция причины отклонения (по умолчанию скрыта) -->
        <div id="rejectionSection" style="display: none; margin-top: 20px;">
            <h3>Причина отклонения</h3>
            <textarea id="rejectionReason" class="rejection-reason"
                placeholder="Укажите причину отклонения заявки"></textarea>
        </div>

        <div class="modal-actions" id="applicationActions">
            <button id="approveBtn" class="main__btn-green">Одобрить</button>
            <button id="rejectBtn" class="main__btn-short">Отклонить</button>
        </div>
    </div>
</div>

<!-- Код для модального окна для просмотра изображений -->
<div id="imageModal" class="modal-image-viewer">
    <span class="modal-image-close">&times;</span>
    <div class="modal-image-content">
        <img class="modal-image" id="fullImage" src="" alt="Фото в полном размере">
        <div class="modal-image-title" id="imageTitle"></div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
<script>
$(document).ready(function() {
    const modal = $('#applicationModal');
    const imageModal = $('#imageModal');
    let currentDriverId = null;

    // Базовый URL для доступа к фотографиям водителей
    const publicUrl = "{{ url('/') }}";
    const storage_base = `${publicUrl}/storage/drivers/`;

    console.log("Базовый URL для хранилища:", storage_base);

    // Data URL изображений для случаев, когда нет доступа к файлам
    const dataUrlImages = {
        car: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTUwIDEwMCI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM1NTU1NTUiLz48dGV4dCB4PSI3NSIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNmZmZmZmYiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiPtCk0L7RgtC+INCw0LLRgtC+PC90ZXh0Pjwvc3ZnPg==",
        passport: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTUwIDEwMCI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM1NTU1NTUiLz48dGV4dCB4PSI3NSIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNmZmZmZmYiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiPtCU0L7QutGD0LzQtdC90YIg0L3QtSDQvdCw0LnQtNC10L08L3RleHQ+PC9zdmc+",
        interior: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTUwIDEwMCI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM1NTU1NTUiLz48dGV4dCB4PSI3NSIgeT0iNTAiIGZvbnQtc2l6ZT0iMTQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNmZmZmZmYiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiPtCh0LDQu9C+0L0g0LDQstGC0L48L3RleHQ+PC9zdmc+"
    };

    // Предзагружаем дефолтные изображения для ускорения отображения
    const defaultImages = {
        passport: "{{ asset('assets/img/disp/passport/1.png') }}" || dataUrlImages.passport,
        car: "{{ asset('assets/img/disp/cars/1.png') }}" || dataUrlImages.car,
        interior: "{{ asset('assets/img/disp/cars/interior.png') }}" || dataUrlImages.interior
    };

    // Проверка доступности изображения
    function checkImageAvailability(url) {
        return new Promise((resolve, reject) => {
            if (!url || url.startsWith('data:')) {
                // Для data URLs сразу возвращаем успех
                resolve(url);
                return;
            }

            const img = new Image();
            img.onload = () => resolve(url);
            img.onerror = () => reject(new Error(`Изображение не доступно: ${url}`));

            // Добавляем случайный параметр для обхода кэширования
            img.src = url + '?t=' + new Date().getTime();

            // Таймаут на случай зависания загрузки
            setTimeout(() => reject(new Error(`Таймаут загрузки: ${url}`)), 5000);
        });
    }

    // Функция для подготовки изображения с проверкой доступности
    async function prepareImageUrl(url, fallbackUrl) {
        try {
            console.log(`Проверка доступности изображения: ${url}`);
            const validatedUrl = await checkImageAvailability(url);
            console.log(`Изображение доступно: ${validatedUrl}`);
            return validatedUrl;
        } catch (error) {
            console.error(`Ошибка загрузки изображения: ${error.message}`);
            return fallbackUrl;
        }
    }

    // Предзагрузка изображения
    function preloadImage(url) {
        if (!url) return null;
        const img = new Image();
        img.src = url;
        console.log(`Предзагрузка изображения: ${url}`);
        return img;
    }

    // Предзагружаем дефолтные изображения
    const preloadedImages = {};
    for (const [key, url] of Object.entries(defaultImages)) {
        preloadedImages[key] = preloadImage(url);
    }

    // Проверяем наличие дефолтных изображений
    const fallbackImage = dataUrlImages.car;

    // Обработчик ошибок для изображений
    function handleImageError(img) {
        img.onerror = function() {
            this.src = fallbackImage;
            this.onerror = null; // Предотвращаем циклические ошибки
        };
        return img;
    }

    // Кэш для уже загруженных данных водителей
    const driversCache = {};

    // Функция для получения правильного пути к изображению
    function getImagePath(driver, fieldName) {
        // Проверяем, существует ли поле в объекте водителя
        if (!driver.hasOwnProperty(fieldName)) {
            console.log(`Поле ${fieldName} отсутствует в объекте водителя, использую заглушку`);
            // Определяем тип заглушки
            if (fieldName.includes('passport') || fieldName.includes('license')) {
                return defaultImages.passport;
            } else if (fieldName.includes('interior')) {
                return defaultImages.interior;
            } else {
                return defaultImages.car;
            }
        }

        // Если значение поля пустое, вернуть дефолтное изображение
        if (!driver[fieldName]) {
            console.log(`Поле ${fieldName} пустое, использую стандартное изображение`);
            // Определяем тип дефолтного изображения
            if (fieldName.includes('passport') || fieldName.includes('license')) {
                return defaultImages.passport;
            } else if (fieldName.includes('interior')) {
                return defaultImages.interior;
            } else {
                return defaultImages.car;
            }
        }

        try {
            // Получаем путь из БД
            const storedPath = driver[fieldName];
            console.log(`Исходный путь из БД (${fieldName}):`, storedPath);

            // Если путь уже содержит полный URL, просто возвращаем его
            if (storedPath.startsWith('http')) {
                console.log(`Путь ${fieldName} уже является URL:`, storedPath);
                return storedPath;
            }

            // Формируем полный URL через asset (более надежно для Laravel)
            let fullPath;

            // Пытаемся исправить проблему с путем, если он не начинается с 'drivers/'
            if (storedPath.includes('drivers/')) {
                // Используем asset для правильного формирования URL
                fullPath = `${publicUrl}/storage/${storedPath}`;
                console.log(`Стандартный путь через publicUrl (${fieldName}):`, fullPath);
            } else {
                // Если путь не содержит 'drivers/', пробуем несколько вариантов
                const possiblePaths = [
                    `${publicUrl}/storage/drivers/${driver.personal_number || driver.id}/${getCategory(fieldName)}/${storedPath}`,
                    `${publicUrl}/storage/${storedPath}`
                ];

                console.log(`Пробуем несколько вариантов путей для ${fieldName}:`, possiblePaths);

                // Используем первый вариант по умолчанию
                fullPath = possiblePaths[0];
            }

            console.log(`Итоговый URL (${fieldName}):`, fullPath);
            return fullPath;
        } catch (e) {
            console.error(`Ошибка при формировании пути для ${fieldName}:`, e);
            // В случае ошибки возвращаем дефолтное изображение
            if (fieldName.includes('passport') || fieldName.includes('license')) {
                return defaultImages.passport;
            } else if (fieldName.includes('interior')) {
                return defaultImages.interior;
            } else {
                return defaultImages.car;
            }
        }
    }

    // Функция для определения категории файла
    function getCategory(fieldName) {
        if (fieldName.includes('passport') || fieldName.includes('license')) {
            return 'documents';
        } else if (fieldName.includes('interior')) {
            return 'interior';
        } else {
            return 'car';
        }
    }

    // Функция для получения текста статуса
    function getStatusText(status) {
        if (!status) return 'Не указано';

        switch (status) {
            case 'submitted':
                return '<span class="status-label status-waiting">В ожидании</span>';
            case 'approved':
                return '<span class="status-label status-approved">Одобрен</span>';
            case 'rejected':
                return '<span class="status-label status-rejected">Отклонен</span>';
            default:
                return status;
        }
    }

    // Обработчик клика на "Посмотреть анкету"
    function loadDriverDetails(driver) {
        console.log("Загрузка данных водителя:", driver);

        // Выводим пути ко всем изображениям для отладки
        ['passport_front', 'passport_back', 'license_front', 'license_back',
            'car_front', 'car_back', 'car_left', 'car_right',
            'car_interior_front', 'car_interior_back'
        ].forEach(field => {
            if (driver[field]) {
                console.log(`Путь к изображению ${field}:`, driver[field]);
            }
        });

        // Очищаем все поля перед заполнением
        $('.modal-value').text('Не указано');

        // Основная информация
        $('#fullName').text(driver.full_name || 'Не указано');
        $('#phone').text(driver.phone || 'Не указано');
        $('#dateOfBirth').text(driver.date_of_birth ? formatDate(driver.date_of_birth) : 'Не указано');

        // Информация о правах
        $('#licenseNumber').text(driver.license_number || 'Не указано');
        $('#licenseIssueDate').text(driver.license_issue_date ? formatDate(driver.license_issue_date) :
            'Не указано');
        $('#licenseExpiryDate').text(driver.license_expiry_date ? formatDate(driver.license_expiry_date) :
            'Не указано');

        // Статус анкеты
        $('#applicationStatus').html(getStatusText(driver.survey_status));

        // Информация об автомобиле
        $('#carBrand').text(driver.car_brand || 'Не указано');
        $('#carModel').text(driver.car_model || 'Не указано');
        $('#carColor').text(driver.car_color || 'Не указано');
        $('#carYear').text(driver.car_year || 'Не указано');
        $('#licensePlate').text(driver.license_plate || 'Не указано');
        $('#vin').text(driver.vin || 'Не указано');
        $('#bodyNumber').text(driver.body_number || 'Не указано');
        $('#sts').text(driver.sts || 'Не указано');
        $('#callsign').text(driver.callsign || 'Не указано');

        // Сервисные параметры
        $('#serviceType').text(driver.service_type || 'Не указано');
        $('#category').text(driver.category || 'Не указано');
        $('#tariff').text(driver.tariff || 'Не указано');
        $('#transmission').text(driver.transmission || 'Не указано');
        $('#boosters').text(driver.boosters ? 'Да' : 'Нет');
        $('#childSeat').text(driver.child_seat ? 'Да' : 'Нет');
        $('#parkingCar').text(driver.parking_car ? 'Да' : 'Нет');
        $('#flashlight').text(driver.has_lightbox ? 'Да' : 'Нет');
        $('#sticker').text(driver.has_nakleyka ? 'Да' : 'Нет');

        addPhotosToGallery(driver);
    }

    // Функция для добавления фотографий в галерею
    function addPhotosToGallery(driver) {
        // Очищаем контейнеры перед добавлением новых фотографий
        $('#personalDocs, #carPhotos, #interiorPhotos').empty();

        // Выводим в консоль информацию о драйвере для отладки
        console.log("Данные водителя для галереи:", driver);
        console.log("Доступные поля в объекте driver:", Object.keys(driver));

        // Добавляем фотографии документов в галерею
        console.log("Добавляю документы в галерею");
        const docFields = [{
                field: 'passport_front',
                name: 'Паспорт (лицевая сторона)'
            },
            {
                field: 'passport_back',
                name: 'Паспорт (обратная сторона)'
            },
            {
                field: 'license_front',
                name: 'ВУ (лицевая сторона)'
            },
            {
                field: 'license_back',
                name: 'ВУ (обратная сторона)'
            },
            {
                field: 'license_photo',
                name: 'Фото с ВУ'
            }
        ];

        docFields.forEach(doc => {
            // Проверяем, существует ли поле в объекте водителя
            if (driver.hasOwnProperty(doc.field)) {
                const imagePath = getImagePath(driver, doc.field);
                console.log(`Добавляю ${doc.name}, путь:`, imagePath);
                addPhotoToGallery('#personalDocs', imagePath, doc.name);
            } else {
                console.log(`Поле ${doc.field} отсутствует, добавляю заглушку`);
                addPhotoToGallery('#personalDocs', defaultImages.passport, doc.name);
            }
        });

        // Фотографии автомобиля
        console.log("Добавляю фото автомобиля в галерею");
        const carPhotosDefaults = [{
                name: 'Спереди',
                field: 'car_front'
            },
            {
                name: 'Сзади',
                field: 'car_back'
            },
            {
                name: 'Слева',
                field: 'car_left'
            },
            {
                name: 'Справа',
                field: 'car_right'
            }
        ];

        carPhotosDefaults.forEach(photo => {
            // Проверяем, существует ли поле в объекте водителя
            if (driver.hasOwnProperty(photo.field)) {
                const imagePath = getImagePath(driver, photo.field);
                console.log(`Добавляю фото авто ${photo.name}, путь:`, imagePath);
                addPhotoToGallery('#carPhotos', imagePath, `Авто ${photo.name}`);
            } else {
                console.log(`Поле ${photo.field} отсутствует, добавляю заглушку`);
                addPhotoToGallery('#carPhotos', defaultImages.car, `Авто ${photo.name}`);
            }
        });

        // Фотографии салона
        console.log("Добавляю фото салона в галерею");
        const interiorPhotosDefaults = [{
                name: 'Передние сидения',
                field: 'car_interior_front'
            },
            {
                name: 'Задние сидения',
                field: 'car_interior_back'
            }
        ];

        interiorPhotosDefaults.forEach(photo => {
            // Проверяем, существует ли поле в объекте водителя
            if (driver.hasOwnProperty(photo.field)) {
                const imagePath = getImagePath(driver, photo.field);
                console.log(`Добавляю фото интерьера ${photo.name}, путь:`, imagePath);
                addPhotoToGallery('#interiorPhotos', imagePath, `Интерьер: ${photo.name}`);
            } else {
                console.log(`Поле ${photo.field} отсутствует, добавляю заглушку`);
                addPhotoToGallery('#interiorPhotos', defaultImages.interior, `Интерьер: ${photo.name}`);
            }
        });
    }

    // Функция для добавления фотографии в указанную галерею
    function addPhotoToGallery(gallerySelector, photoUrl, photoName) {
        console.log(`Добавляю фото "${photoName}" в галерею ${gallerySelector}, URL:`, photoUrl);

        // Проверяем, существует ли галерея
        const gallery = $(gallerySelector);
        if (gallery.length === 0) {
            console.error(`Галерея ${gallerySelector} не найдена в DOM!`);
            return;
        }

        // Определяем тип изображения по имени для выбора правильного fallback
        let fallbackType = 'car';
        if (photoName.toLowerCase().includes('паспорт') || photoName.toLowerCase().includes('ву')) {
            fallbackType = 'passport';
        } else if (photoName.toLowerCase().includes('сидени') || photoName.toLowerCase().includes('интерьер')) {
            fallbackType = 'interior';
        }

        // Используем Data URL если фото недоступно
        const fallbackSrc = dataUrlImages[fallbackType];

        // Создаем уникальный идентификатор для отслеживания
        const imgId = 'img_' + Math.random().toString(36).substr(2, 9);

        // Создаем элементы HTML для карточки с фотографией с проверкой ошибок
        const photoHtml = `
        <div class="photo-item">
            <img id="${imgId}" 
                src="${photoUrl}" 
                alt="${photoName}" 
                data-fallback="${fallbackSrc}"
                onerror="this.onerror=null; this.src='${fallbackSrc}'; console.error('Ошибка загрузки изображения:', '${photoUrl}');"
                onload="console.log('Успешно загружено изображение:', '${photoUrl}');" />
            <div class="photo-label">${photoName}</div>
        </div>`;

        // Добавляем в галерею
        gallery.append(photoHtml);

        // Проверяем, был ли элемент добавлен успешно
        const addedImage = $(`#${imgId}`);
        if (addedImage.length === 0) {
            console.error(`Ошибка добавления изображения ${photoName} в галерею!`);
        } else {
            console.log(`Элемент с ID ${imgId} успешно добавлен в галерею ${gallerySelector}`);

            // Также проверяем изображение через JavaScript
            const img = new Image();
            img.onload = function() {
                console.log(`JS проверка: Изображение ${photoName} загружено успешно`);
            };
            img.onerror = function() {
                console.error(`JS проверка: Ошибка загрузки изображения ${photoName}`);
                // Замена на fallback если произошла ошибка
                addedImage.attr('src', fallbackSrc);
            };
            // Добавляем случайный параметр для обхода кэширования
            img.src = photoUrl + '?t=' + new Date().getTime();
        }
    }

    // Поиск по заявкам
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('.main__card-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Форматирование даты
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('ru-RU');
    }

    // Анимация карточек при загрузке страницы
    $('.main__card-item').each(function(index) {
        const $this = $(this);
        setTimeout(function() {
            $this.css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).animate({
                'opacity': '1',
                'transform': 'translateY(0)'
            }, index * 100);
        }, index * 100);
    });

    // Обработка изменения фильтра
    $('#statusFilter').on('change', function() {
        const selectedStatus = $(this).val();

        // Показать все заявки, если выбран вариант "Все заявки"
        if (selectedStatus === 'all') {
            $('.main__card-item').show();
            $('#noApplicationsMessage').remove();
            return;
        }

        // Скрыть все заявки, затем показать только те, которые соответствуют выбранному статусу
        $('.main__card-item').hide();
        $(`.main__card-item[data-status="${selectedStatus}"]`).show();

        // Если нет заявок с выбранным статусом, показать сообщение
        if ($(`.main__card-item[data-status="${selectedStatus}"]`).length === 0) {
            if ($('#noApplicationsMessage').length === 0) {
                $('#applicationsContainer').append(
                    `<p id="noApplicationsMessage" class="default__cars">
                        Нет заявок со статусом "${selectedStatus === 'submitted' ? 'В ожидании' : 
                        (selectedStatus === 'approved' ? 'Одобренные' : 'Отклоненные')}"
                    </p>`
                );
            }
        } else {
            $('#noApplicationsMessage').remove();
        }
    });

    // Функция обновления статуса в модальном окне
    function updateModalStatus(status) {
        // Показываем или скрываем кнопки одобрения/отклонения в зависимости от статуса
        if (status === 'submitted') {
            $('#applicationActions').show();
        } else {
            $('#applicationActions').hide();
        }

        // Устанавливаем статус заявки
        $('#applicationStatus').html(getStatusText(status));
    }

    // Обработка клика на кнопку одобрения заявки (в списке заявок)
    $(document).on('click', '.approve-application', function() {
        const driverId = $(this).data('id');
        approveApplication(driverId);
    });

    // Обработка клика на кнопку отклонения заявки (в списке заявок)
    $(document).on('click', '.reject-application', function() {
        const driverId = $(this).data('id');
        currentDriverId = driverId;

        // Открываем модальное окно с формой для указания причины отклонения
        modal.show().addClass('show');
        $('#rejectionSection').show();
        $('#applicationActions').hide();

        // Проверяем, есть ли данные в кэше
        if (driversCache[driverId]) {
            loadDriverDetails(driversCache[driverId]);

            // Добавляем кнопку подтверждения отклонения, если её еще нет
            if ($('#confirmRejectBtn').length === 0) {
                $('<button id="confirmRejectBtn" class="main__btn-short">Подтвердить отклонение</button>')
                    .insertAfter('#rejectionReason');
            }
            return;
        }

        // Загружаем данные водителя для отображения в модальном окне
        $.ajax({
            url: `${publicUrl}/backend/disp/driver-application/${driverId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const driver = response.driver;
                    // Сохраняем в кэш
                    driversCache[driverId] = driver;

                    loadDriverDetails(driver);

                    // Добавляем кнопку подтверждения отклонения, если её еще нет
                    if ($('#confirmRejectBtn').length === 0) {
                        $('<button id="confirmRejectBtn" class="main__btn-short">Подтвердить отклонение</button>')
                            .insertAfter('#rejectionReason');
                    }
                } else {
                    alert('Не удалось загрузить данные заявки');
                    modal.removeClass('show');
                    setTimeout(() => modal.hide(), 300);
                }
            },
            error: function(xhr) {
                console.error('Ошибка загрузки данных:', xhr.responseText);
                alert('Произошла ошибка при загрузке данных');
                modal.removeClass('show');
                setTimeout(() => modal.hide(), 300);
            }
        });
    });

    // Функция одобрения заявки
    function approveApplication(driverId) {
        if (confirm('Вы уверены, что хотите одобрить эту заявку?')) {
            $.ajax({
                url: `${publicUrl}/backend/disp/driver-application/${driverId}/approve`,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Обновляем кэш
                        if (driversCache[driverId]) {
                            driversCache[driverId].survey_status = 'approved';
                        }

                        alert('Заявка успешно одобрена');
                        // Скрываем модальное окно и обновляем страницу
                        modal.removeClass('show');
                        setTimeout(() => {
                            modal.hide();
                            location.reload();
                        }, 300);
                    } else {
                        alert('Не удалось одобрить заявку: ' + (response.error ||
                            'Неизвестная ошибка'));
                    }
                },
                error: function(xhr) {
                    console.error('Ошибка одобрения заявки:', xhr.responseText);
                    alert('Произошла ошибка при одобрении заявки');
                }
            });
        }
    }

    // Обработка клика на кнопку одобрения внутри модального окна
    $('#approveBtn').click(function() {
        if (currentDriverId) {
            approveApplication(currentDriverId);
        }
    });

    // Обработка клика на кнопку отклонения в модальном окне
    $('#rejectBtn').click(function() {
        $('#rejectionSection').show();
        $('#applicationActions').hide();

        // Добавляем кнопку подтверждения отклонения, если её еще нет
        if ($('#confirmRejectBtn').length === 0) {
            $('<button id="confirmRejectBtn" class="main__btn-short">Подтвердить отклонение</button>')
                .insertAfter('#rejectionReason');
        }
    });

    // Обработка клика на кнопку подтверждения отклонения (динамически добавляемая кнопка)
    $(document).on('click', '#confirmRejectBtn', function() {
        const rejectionReason = $('#rejectionReason').val();
        if (!rejectionReason) {
            alert('Пожалуйста, укажите причину отказа');
            return;
        }

        $.ajax({
            url: `${publicUrl}/backend/disp/driver-application/${currentDriverId}/reject`,
            type: 'POST',
            dataType: 'json',
            data: {
                rejection_reason: rejectionReason,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Обновляем кэш
                    if (driversCache[currentDriverId]) {
                        driversCache[currentDriverId].survey_status = 'rejected';
                        driversCache[currentDriverId].rejection_reason = rejectionReason;
                    }

                    alert('Заявка отклонена');
                    // Скрываем модальное окно и обновляем страницу с анимацией
                    modal.removeClass('show');
                    setTimeout(() => {
                        modal.hide();
                        location.reload();
                    }, 300);
                } else {
                    alert('Не удалось отклонить заявку: ' + (response.error ||
                        'Неизвестная ошибка'));
                }
            },
            error: function(xhr) {
                console.error('Ошибка отклонения заявки:', xhr.responseText);
                alert('Произошла ошибка при отклонении заявки: ' + xhr
                    .responseText);
            }
        });
    });

    // Обработка клика на миниатюру для увеличения изображения
    $(document).on('click', '.photo-item img', function() {
        const imgSrc = $(this).attr('src');
        const imgTitle = $(this).closest('.photo-item').find('.photo-label').text();

        $('#fullImage').attr('src', imgSrc);
        $('#imageTitle').text(imgTitle);

        imageModal.show();
        setTimeout(() => {
            imageModal.addClass('show');
        }, 10);
    });

    // Закрытие увеличенного изображения
    $('.modal-image-close').click(function() {
        imageModal.removeClass('show');
        setTimeout(() => {
            imageModal.hide();
        }, 300);
    });

    // Закрытие увеличенного изображения на ESC
    $(document).keydown(function(e) {
        if (e.key === "Escape") {
            imageModal.removeClass('show');
            setTimeout(() => {
                imageModal.hide();
            }, 300);
        }
    });

    // Отображение модального окна с данными заявки
    $(document).on('click', '.view-application', function() {
        console.log("Кнопка 'Посмотреть анкету' нажата");
        const driverId = $(this).data('id');
        currentDriverId = driverId;
        console.log("ID водителя:", driverId);

        // Сначала показываем модальное окно с анимацией для улучшения UX
        modal.show().addClass('show');
        console.log("Модальное окно отображено");

        // Очищаем галереи перед загрузкой новых фотографий
        $('#personalDocs, #carPhotos, #interiorPhotos').empty();
        console.log("Контейнеры галерей очищены");

        // Проверяем, есть ли данные в кэше
        if (driversCache[driverId]) {
            console.log("Используем данные из кэша:", driversCache[driverId]);
            loadDriverDetails(driversCache[driverId]);
            updateModalStatus(driversCache[driverId].survey_status);

            // Проверяем содержимое контейнеров после загрузки
            setTimeout(() => {
                console.log("Количество фото в personalDocs:", $('#personalDocs .photo-item')
                    .length);
                console.log("Количество фото в carPhotos:", $('#carPhotos .photo-item').length);
                console.log("Количество фото в interiorPhotos:", $(
                    '#interiorPhotos .photo-item').length);
            }, 500);

            return;
        }

        // Загружаем данные водителя
        console.log("Загрузка данных водителя с сервера...");
        $.ajax({
            url: `${publicUrl}/backend/disp/driver-application/${driverId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const driver = response.driver;
                    console.log("Данные получены от сервера:", driver);
                    console.log("Доступные поля в объекте driver:", Object.keys(driver));

                    // Выводим отладочную информацию о файлах
                    if (response.debug) {
                        console.log("Отладочная информация о файлах:", response.debug);
                        // Для каждого файла в отладочной информации
                        for (const [field, info] of Object.entries(response.debug)) {
                            console.log(`Файл ${field}:`, info);
                            // Если файл не существует на сервере, используем URL из asset
                            if (!info.exists && driver[field]) {
                                driver[field] = info.url;
                                console.log(`URL файла ${field} обновлен на:`, info.url);
                            }
                        }
                    }

                    // Сохраняем в кэш
                    driversCache[driverId] = driver;

                    loadDriverDetails(driver);
                    updateModalStatus(driver.survey_status);

                    // Проверяем содержимое контейнеров после загрузки
                    setTimeout(() => {
                        console.log("Количество фото в personalDocs:", $(
                            '#personalDocs .photo-item').length);
                        console.log("Количество фото в carPhotos:", $(
                            '#carPhotos .photo-item').length);
                        console.log("Количество фото в interiorPhotos:", $(
                            '#interiorPhotos .photo-item').length);
                    }, 500);
                } else {
                    console.error("Ошибка в ответе сервера:", response);
                    alert('Не удалось загрузить данные заявки');
                    modal.removeClass('show');
                    setTimeout(() => modal.hide(), 300);
                }
            },
            error: function(xhr) {
                console.error('Ошибка загрузки данных:', xhr.responseText);
                alert('Произошла ошибка при загрузке данных');
                modal.removeClass('show');
                setTimeout(() => modal.hide(), 300);
            }
        });
    });

    // Закрытие модального окна
    $('.close, #cancelBtn').click(function() {
        modal.removeClass('show');
        setTimeout(() => {
            modal.hide();
            $('#rejectionSection').hide();
        }, 300);
    });
});
</script>
@endpush