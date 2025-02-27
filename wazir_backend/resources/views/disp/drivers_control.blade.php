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

/* Стили для модального окна */
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

/* Стили для галереи фотографий */
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

/* Стиль для модального изображения при клике на миниатюру */
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
                <select name="filing-date">
                    <option value="Дата подачи" disabled selected>Статусы</option>
                    <option value="Занят">Занят</option>
                    <option value="В ожидании">В ожидании</option>
                </select>
            </form>
        </div>
    </div>
    <div class="main__subheader-drivers">
        <div class="main__header-tags main__subheader-drivers-tags">
            <ul>
                <li>На линии 26 водителей</li>
                <li><span class="status-span free"></span> 12 свободный</li>
                <li><span class="status-span busy"></span> 14 занят</li>
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

<div class="main__cards-wrapper">
    @if(isset($pendingApplications) && count($pendingApplications) > 0)
    @foreach($pendingApplications as $application)
    <div class="main__card-item">
        <img src="{{ asset('assets/img/disp/passport/1.png') }}" alt="driver">
        <button class="main__btn">{{ $application->full_name ?? 'Имя не указано' }}</button>
        <button class="main__btn">Дата регистрации: {{ $application->created_at->format('d.m.Y') }}</button>
        <button class="main__btn view-application" data-id="{{ $application->id }}">Посмотреть анкету</button>
        <div class="main__card-btn">
            <button class="main__btn-green approve-application" data-id="{{ $application->id }}">Одобрить</button>
            <button class="main__btn-short reject-application" data-id="{{ $application->id }}">Отменить</button>
        </div>
    </div>
    @endforeach
    @else
    <p class="default__cars">
        Пока нету активных заявок
    </p>
    @endif
</div>

<!-- Модальное окно для просмотра анкеты -->
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

            <!-- Галерея для фотографий паспорта -->
            <h4 style="margin-top: 10px; color: #ddd;">Документы пользователя:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="personalDocs">
                    <!-- Здесь будут фотографии паспорта и ВУ -->
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

            <!-- Галерея для фотографий автомобиля -->
            <h4 style="margin-top: 10px; color: #ddd;">Фотографии автомобиля:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="carPhotos">
                    <!-- Здесь будут фотографии автомобиля -->
                </div>
            </div>

            <!-- Галерея для фотографий салона -->
            <h4 style="margin-top: 10px; color: #ddd;">Фотографии салона:</h4>
            <div class="photo-gallery">
                <div class="photo-gallery-container" id="interiorPhotos">
                    <!-- Здесь будут фотографии салона -->
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
                <div class="modal-label">Парковая машина:</div>
                <div class="modal-value" id="parkingCar"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Наклейка:</div>
                <div class="modal-value" id="hasNakleyka"></div>
            </div>
            <div class="modal-row">
                <div class="modal-label">Фонарь:</div>
                <div class="modal-value" id="hasLightbox"></div>
            </div>
        </div>

        <div id="rejectionSection" style="display: none;">
            <h3>Причина отказа</h3>
            <textarea id="rejectionReason" class="rejection-reason" rows="4"
                placeholder="Укажите причину отказа"></textarea>
        </div>

        <div class="modal-actions">
            <button class="main__btn-green" id="approveBtn">Одобрить</button>
            <button class="main__btn-short" id="rejectBtn">Отклонить</button>
            <button class="main__btn" id="cancelBtn">Закрыть</button>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра увеличенных изображений -->
<div id="imageViewerModal" class="modal-image-viewer">
    <span class="modal-image-close">&times;</span>
    <div class="modal-image-content">
        <img class="modal-image" id="modalImage" src="" alt="Увеличенное изображение">
        <div class="modal-image-title" id="modalImageTitle"></div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
<script>
$(document).ready(function() {
    let currentDriverId = null;
    const modal = $('#applicationModal');
    const imageModal = $('#imageViewerModal');

    // Открытие модального окна для просмотра анкеты
    $(document).on('click', '.view-application', function() {
        const driverId = $(this).data('id');
        currentDriverId = driverId;
        loadDriverDetails(driverId);
    });

    // Закрытие модального окна
    $('.close, #cancelBtn').click(function() {
        modal.removeClass('show');
        setTimeout(() => {
            modal.hide();
            $('#rejectionSection').hide();
        }, 300);
    });

    // Нажатие на кнопку "Отклонить" в модальном окне
    $('#rejectBtn').click(function() {
        $('#rejectionSection').show();
    });

    // Одобрение заявки из карточки
    $(document).on('click', '.approve-application', function() {
        const driverId = $(this).data('id');
        approveApplication(driverId);
    });

    // Отклонение заявки из карточки
    $(document).on('click', '.reject-application', function() {
        const driverId = $(this).data('id');
        currentDriverId = driverId;
        modal.show();
        setTimeout(() => {
            modal.addClass('show');
        }, 10);
        loadDriverDetails(driverId);
        $('#rejectionSection').show();
    });

    // Одобрение заявки из модального окна
    $('#approveBtn').click(function() {
        if (currentDriverId) {
            approveApplication(currentDriverId);
        }
    });

    // Обработка клика на миниатюру для увеличения изображения
    $(document).on('click', '.photo-item img', function() {
        const imgSrc = $(this).attr('src');
        const imgTitle = $(this).closest('.photo-item').find('.photo-label').text();

        $('#modalImage').attr('src', imgSrc);
        $('#modalImageTitle').text(imgTitle);

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

    // Функция загрузки данных водителя
    function loadDriverDetails(driverId) {
        $.ajax({
            url: "{{ url('/backend/disp/driver-application') }}/" + driverId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const driver = response.driver;

                    // Заполняем личную информацию
                    $('#fullName').text(driver.full_name || 'Не указано');
                    $('#phone').text(driver.phone || 'Не указано');
                    $('#dateOfBirth').text(driver.date_of_birth ? formatDate(driver.date_of_birth) :
                        'Не указано');

                    // Заполняем информацию о правах
                    $('#licenseNumber').text(driver.license_number || 'Не указано');
                    $('#licenseIssueDate').text(driver.license_issue_date ? formatDate(driver
                        .license_issue_date) : 'Не указано');
                    $('#licenseExpiryDate').text(driver.license_expiry_date ? formatDate(driver
                        .license_expiry_date) : 'Не указано');

                    // Заполняем информацию о транспортном средстве из связанной модели
                    if (driver.vehicle) {
                        $('#carBrand').text(driver.vehicle.vehicle_brand || 'Не указано');
                        $('#carModel').text(driver.vehicle.vehicle_model || 'Не указано');
                        $('#carColor').text(driver.vehicle.vehicle_color || 'Не указано');
                        $('#carYear').text(driver.vehicle.vehicle_year || 'Не указано');
                        $('#licensePlate').text(driver.vehicle.vehicle_state_number ||
                            'Не указано');
                        $('#vin').text(driver.vehicle.chassis_number || 'Не указано');
                        $('#bodyNumber').text(driver.vehicle.stir || 'Не указано');
                        $('#sts').text(driver.vehicle.sts || 'Не указано');
                    } else {
                        // Если связанных данных нет, используем данные из водителя
                        $('#carBrand').text(driver.car_brand || 'Не указано');
                        $('#carModel').text(driver.car_model || 'Не указано');
                        $('#carColor').text(driver.car_color || 'Не указано');
                        $('#carYear').text(driver.car_year || 'Не указано');
                        $('#licensePlate').text(driver.license_plate || 'Не указано');
                        $('#vin').text(driver.vin || 'Не указано');
                        $('#bodyNumber').text(driver.body_number || 'Не указано');
                        $('#sts').text(driver.sts || 'Не указано');
                    }

                    // Заполняем параметры сервиса
                    $('#callsign').text(driver.callsign || 'Не указано');
                    $('#serviceType').text(driver.service_type || 'Не указано');
                    $('#category').text(driver.category || 'Не указано');
                    $('#tariff').text(driver.tariff || 'Не указано');
                    $('#transmission').text(driver.transmission || 'Не указано');
                    $('#childSeat').text(driver.child_seat ? 'Да' : 'Нет');
                    $('#boosters').text(driver.boosters ? 'Да' : 'Нет');
                    $('#parkingCar').text(driver.parking_car ? 'Да' : 'Нет');
                    $('#hasNakleyka').text(driver.has_nakleyka ? 'Да' : 'Нет');
                    $('#hasLightbox').text(driver.has_lightbox ? 'Да' : 'Нет');

                    // Добавляем фотографии документов и автомобиля
                    addPhotosToGallery(driver);

                    // Показываем модальное окно с анимацией
                    modal.show();
                    setTimeout(() => {
                        modal.addClass('show');
                    }, 10);
                } else {
                    alert('Не удалось загрузить данные заявки');
                }
            },
            error: function() {
                alert('Произошла ошибка при загрузке данных');
            }
        });
    }

    // Функция для добавления фотографий в галерею
    function addPhotosToGallery(driver) {
        // Очищаем контейнеры перед добавлением новых фотографий
        $('#personalDocs, #carPhotos, #interiorPhotos').empty();

        // Пути к фотографиям
        const storage_base = "{{ asset('storage') }}/";

        // Добавляем фотографии документов (паспорт и водительское удостоверение)
        if (driver.passport_front) {
            addPhotoToGallery('#personalDocs', storage_base + driver.passport_front,
                'Паспорт (лицевая сторона)');
        } else {
            // Используем заглушку если фото нет
            addPhotoToGallery('#personalDocs', "{{ asset('assets/img/disp/passport/1.png') }}",
                'Паспорт (лицевая сторона)');
        }

        if (driver.passport_back) {
            addPhotoToGallery('#personalDocs', storage_base + driver.passport_back,
                'Паспорт (обратная сторона)');
        } else {
            addPhotoToGallery('#personalDocs', "{{ asset('assets/img/disp/passport/1.png') }}",
                'Паспорт (обратная сторона)');
        }

        if (driver.license_front) {
            addPhotoToGallery('#personalDocs', storage_base + driver.license_front, 'ВУ (лицевая сторона)');
        } else {
            addPhotoToGallery('#personalDocs', "{{ asset('assets/img/disp/passport/1.png') }}",
                'ВУ (лицевая сторона)');
        }

        if (driver.license_back) {
            addPhotoToGallery('#personalDocs', storage_base + driver.license_back, 'ВУ (обратная сторона)');
        } else {
            addPhotoToGallery('#personalDocs', "{{ asset('assets/img/disp/passport/1.png') }}",
                'ВУ (обратная сторона)');
        }

        // Фотографии автомобиля
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
            if (driver[photo.field]) {
                addPhotoToGallery('#carPhotos', storage_base + driver[photo.field],
                    `Авто ${photo.name}`);
            } else {
                addPhotoToGallery('#carPhotos', "{{ asset('assets/img/disp/cars/1.png') }}",
                    `Авто ${photo.name}`);
            }
        });

        // Фотографии салона
        const interiorPhotosDefaults = [{
                name: 'Передние сидения',
                field: 'interior_front'
            },
            {
                name: 'Задние сидения',
                field: 'interior_back'
            }
        ];

        interiorPhotosDefaults.forEach(photo => {
            if (driver[photo.field]) {
                addPhotoToGallery('#interiorPhotos', storage_base + driver[photo.field], photo.name);
            } else {
                addPhotoToGallery('#interiorPhotos', "{{ asset('assets/img/disp/cars/interior.png') }}",
                    photo.name);
            }
        });
    }

    // Функция для добавления фотографии в указанную галерею
    function addPhotoToGallery(gallerySelector, photoUrl, photoName) {
        const photoItem = `
            <div class="photo-item">
                <img src="${photoUrl}" alt="${photoName}">
                <div class="photo-label">${photoName}</div>
            </div>
        `;
        $(gallerySelector).append(photoItem);
    }

    // Функция одобрения заявки
    function approveApplication(driverId) {
        if (confirm('Вы уверены, что хотите одобрить эту заявку?')) {
            $.ajax({
                url: "{{ url('/backend/disp/driver-application') }}/" + driverId + "/approve",
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Заявка успешно одобрена');
                        // Скрываем модальное окно и обновляем страницу
                        modal.removeClass('show');
                        setTimeout(() => {
                            modal.hide();
                            location.reload();
                        }, 300);
                    } else {
                        alert('Не удалось одобрить заявку');
                    }
                },
                error: function() {
                    alert('Произошла ошибка при одобрении заявки');
                }
            });
        }
    }

    // Обработка отклонения заявки
    $(document).on('submit', '#rejectForm', function(e) {
        e.preventDefault();

        const rejectionReason = $('#rejectionReason').val();
        if (!rejectionReason) {
            alert('Пожалуйста, укажите причину отказа');
            return;
        }

        $.ajax({
            url: "{{ url('/backend/disp/driver-application') }}/" + currentDriverId + "/reject",
            type: 'POST',
            dataType: 'json',
            data: {
                rejection_reason: rejectionReason
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Заявка отклонена');
                    // Скрываем модальное окно и обновляем страницу
                    modal.removeClass('show');
                    setTimeout(() => {
                        modal.hide();
                        location.reload();
                    }, 300);
                } else {
                    alert('Не удалось отклонить заявку');
                }
            },
            error: function() {
                alert('Произошла ошибка при отклонении заявки');
            }
        });
    });

    // Обработка клика на кнопку отклонения в модальном окне
    $('#rejectBtn').click(function() {
        $('#rejectionSection').show();

        // Добавляем обработчик для непосредственного отклонения после ввода причины
        const submitRejection = function() {
            const rejectionReason = $('#rejectionReason').val();
            if (!rejectionReason) {
                alert('Пожалуйста, укажите причину отказа');
                return;
            }

            $.ajax({
                url: "{{ url('/backend/disp/driver-application') }}/" + currentDriverId +
                    "/reject",
                type: 'POST',
                dataType: 'json',
                data: {
                    rejection_reason: rejectionReason,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
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
                    alert('Произошла ошибка при отклонении заявки: ' + xhr
                        .responseText);
                }
            });
        };

        // Добавляем кнопку подтверждения отклонения
        if ($('#confirmRejectBtn').length === 0) {
            $('<button id="confirmRejectBtn" class="main__btn-short">Подтвердить отклонение</button>')
                .insertAfter('#rejectionReason')
                .click(submitRejection);
        }
    });

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
            }, 300);
        }, index * 100);
    });
});
</script>
@endpush