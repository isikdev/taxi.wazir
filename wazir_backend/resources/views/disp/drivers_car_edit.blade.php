@extends('disp.layout')
@section('title', 'Создание водителя - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.personal-data-form .form-column .upload-group .upload-box .upload-icon {
    display: inline-block;
    color: #666;
    display: flex;
    width: 30px;
    height: 50px;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    align-self: center;
}

.personal-data-form .form-column .upload-group .upload-label {
    font-weight: 400;
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__subheader-drivers {
    width: unset;
}

.invalid {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}

/* Класс превью */
.upload-preview {
    margin-top: 10px;
    border: 1px solid red;
    max-width: 100%;
    width: 100%;
    height: 400px;
}

.upload-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Прогресс-бар */
.upload-progress {
    margin-top: 5px;
    width: 100%;
    background-color: #e0e0e0;
    border-radius: 4px;
}

.upload-progress .progress-bar {
    width: 0%;
    height: 10px;
    background-color: #4caf50;
    border-radius: 4px;
    color: #fff;
    text-align: right;
    font-size: 22px;
}

.upload-label-wrapper {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.personal-data-form-wrapper {
    margin-top: 20px;
}

.personal-data-form-wrapper-main {
    display: flex;
    justify-content: space-between;
}

.personal-data-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

.form-column {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;
}

.form-group.upload-group {
    width: 100%;
    flex-direction: column;
    padding: 10px;
    border-radius: 4px;
}

.upload-label {
    font-weight: bold;
    line-height: 1.4em;
}

.upload-box {
    position: relative;
    cursor: pointer;
}

.upload-icon {
    padding: 5px 10px;
    border-radius: 4px;
    margin-left: 10px;
    font-size: 1.2em;
    background: #333;
    color: #fff;
}

.action-buttons {
    margin-top: 20px;
}

.action-buttons .main__btn-green {
    background-color: #4caf50;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.action-buttons .main__btn-green:hover {
    background-color: #45a049;
}
</style>
@endpush

@section('content')
<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Водитель</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_num_edit', ['driver' => $driver->id]) }}">Автомобиль</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
                <a href="#">Фото автомобиля</a>
            </button>
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

<div class="main__driversedit-wrapper">
    <h3 class="title">Фото автомобиля</h3>

    <!-- ОДНА форма, 7 полей -->
    <form class="personal-data-form-wrapper"
        action="{{ route('dispatcher.backend.process_drivers_car_edit', ['driver' => $driver->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="personal-data-form-wrapper-main">
            <div class="personal-data-form">
                <div class="form-column">
                    <!-- 1) Фото автомобиля (Автомобиль спереди) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото Автомобиля<br>(Автомобиль спереди)
                            </div>
                            <label class="upload-box">
                                <!-- Обязательно уникальное name="car_front" -->
                                <input type="file" name="car_front" class="file-input" required
                                    data-preview-target="#car_front_preview"
                                    data-progress-target="#car_front_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_front')
                        <p class="invalid">{{ $message }}</p>
                        @enderror

                        <!-- Превью -->
                        <div id="car_front_preview" class="upload-preview"></div>

                        <!-- Прогресс-бар -->
                        <div id="car_front_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <!-- 2) Фото автомобиля (Автомобиль сзади) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото автомобиля <br>(Автомобиль сзади)
                            </div>
                            <label class="upload-box">
                                <!-- name="car_back" -->
                                <input type="file" name="car_back" class="file-input" required
                                    data-preview-target="#car_back_preview" data-progress-target="#car_back_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_back')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="car_back_preview" class="upload-preview"></div>
                        <div id="car_back_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <!-- 3) Фото с ВУ (Водительское удостоверение) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото с ВУ<br>(Водительское удостоверение)
                            </div>
                            <label class="upload-box">
                                <!-- name="license_photo" -->
                                <input type="file" name="license_photo" class="file-input" required
                                    data-preview-target="#license_photo_preview"
                                    data-progress-target="#license_photo_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('license_photo')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="license_photo_preview" class="upload-preview"></div>
                        <div id="license_photo_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <!-- 4) Фото автомобиля (справа) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото автомобиля<br>(Автомобиль справа)
                            </div>
                            <label class="upload-box">
                                <!-- name="car_right" -->
                                <input type="file" name="car_right" class="file-input" required
                                    data-preview-target="#car_right_preview"
                                    data-progress-target="#car_right_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_right')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="car_right_preview" class="upload-preview"></div>
                        <div id="car_right_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                </div>

                <div class="form-column">
                    <!-- 5) Фото автомобиля (слева) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото автомобиля<br>(Автомобиль слева)
                            </div>
                            <label class="upload-box">
                                <!-- name="car_left" -->
                                <input type="file" name="car_left" class="file-input" required
                                    data-preview-target="#car_left_preview" data-progress-target="#car_left_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_left')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="car_left_preview" class="upload-preview"></div>
                        <div id="car_left_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <!-- 6) Фото Салона (спереди) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото Салона<br>Спереди
                            </div>
                            <label class="upload-box">
                                <!-- name="car_interior_front" -->
                                <input type="file" name="car_interior_front" class="file-input" required
                                    data-preview-target="#car_interior_front_preview"
                                    data-progress-target="#car_interior_front_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_interior_front')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="car_interior_front_preview" class="upload-preview"></div>
                        <div id="car_interior_front_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>

                    <!-- 7) Фото Салона (сзади) -->
                    <div class="form-group upload-group">
                        <div class="upload-label-wrapper">
                            <div class="upload-label">
                                Фото Салона<br>Сзади
                            </div>
                            <label class="upload-box">
                                <!-- name="car_interior_back" -->
                                <input type="file" name="car_interior_back" class="file-input" required
                                    data-preview-target="#car_interior_back_preview"
                                    data-progress-target="#car_interior_back_progress" />
                                <span class="upload-icon">+</span>
                            </label>
                        </div>
                        @error('car_interior_back')
                        <p class="invalid">{{ $message }}</p>
                        @enderror
                        <div id="car_interior_back_preview" class="upload-preview"></div>
                        <div id="car_interior_back_progress" class="upload-progress">
                            <div class="progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Кнопка отправки формы (загрузить все фото разом) -->
        <div class="action-buttons">
            <button type="submit" class="main__btn-green">Сохранить</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    function handleFileInputChange(input) {
        var file = input.files[0];
        if (file) {
            var previewTarget = $(input).data('preview-target');
            var progressTarget = $(input).data('progress-target');
            var reader = new FileReader();

            // Отслеживаем прогресс чтения (не загрузки на сервер, а чтения в браузере)
            reader.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percentLoaded = Math.round((e.loaded / e.total) * 100);
                    $(progressTarget + " .progress-bar").css("width", percentLoaded + "%").text(
                        percentLoaded + "%");
                }
            };

            // Когда файл прочитан — выводим картинку в preview
            reader.onload = function(e) {
                $(previewTarget).html('<img src="' + e.target.result + '" alt="Preview">');
            };

            reader.readAsDataURL(file);
        }
    }

    // Вешаем обработчик на все input.file-input
    $('.file-input').on('change', function() {
        handleFileInputChange(this);
    });
});
</script>
@endpush