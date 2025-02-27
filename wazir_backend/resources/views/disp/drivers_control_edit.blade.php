@extends('disp.layout')
@section('title', 'Создание водителя - taxi.wazir.kg')
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

.main__subheader-drivers {
    width: unset;
}

.invalid {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}

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
}

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

.personal-data-form .form-column .upload-group {
    width: 100%;
    flex-direction: column;
}

/* Если требуется, чтобы текст в некоторых полях был всегда в верхнем регистре */
#personal-number {
    text-transform: uppercase;
}

/* Делаем ссылки неактивными, если запись ещё не создана */
.disabled-link {
    pointer-events: none;
    opacity: 0.5;
}
</style>
@endpush
@section('content')

<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
                <a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Водитель</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ (isset($driver->id) && $driver->id) ? route('dispatcher.backend.drivers_num_edit', ['driver' => $driver->id]) : '#' }}"
                    class="{{ (!isset($driver->id) || !$driver->id) ? 'disabled-link' : '' }}">Автомобиль</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ (isset($driver->id) && $driver->id) ? route('dispatcher.backend.drivers_car_edit', ['driver' => $driver->id]) : '#' }}"
                    class="{{ (!isset($driver->id) || !$driver->id) ? 'disabled-link' : '' }}">Фото автомобиля</a>
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
    <h3 class="title">Персональные данные</h3>
    <form class="personal-data-form-wrapper" action="{{ route('dispatcher.backend.process_drivers_control_edit') }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <div class="personal-data-form">
            <div class="form-column">
                <!-- Ф.И.О. -->
                <div class="form-group">
                    <label for="fio">Ф.И.О.</label>
                    <input type="text" id="fio" name="full_name" placeholder="Лавров Сергей Викторович"
                        autocomplete="off" value="{{ old('full_name') }}" />
                    @error('full_name')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Дата рождения -->
                <div class="form-group">
                    <label for="dob">Дата рождения</label>
                    <input type="text" id="dob" name="date_of_birth" placeholder="26.06.2024" autocomplete="off"
                        value="{{ old('date_of_birth') }}" />
                    @error('date_of_birth')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Телефон -->
                <div class="form-group">
                    <label for="phone">Номер телефона</label>
                    <input type="text" id="phone" name="phone" placeholder="+996 000 00 00 00" autocomplete="off"
                        value="{{ old('phone', $driver->phone ?? '') }}" />
                    @error('phone')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Персональный номер -->
                <div class="form-group">
                    <label for="personal-number">Персональный номер</label>
                    <input type="text" id="personal-number" name="personal_number" placeholder="2222819937793" readonly
                        value="{{ old('personal_number', $personal_number ?? Str::random(17)) }}" />
                    @error('personal_number')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Загрузите ID (Паспорт) - Лицевая сторона -->
                <div class="form-group upload-group">
                    <div class="upload-label-wrapper">
                        <div class="upload-label">
                            Загрузите ID<br>(Персональный Паспорт)<br>Лицевая сторона
                        </div>
                        <label class="upload-box">
                            <input type="file" name="passport_front" class="file-input" required
                                data-preview-target="#passport_front_preview"
                                data-progress-target="#passport_front_progress" />
                            <span class="upload-icon">+</span>
                        </label>
                    </div>
                    @error('passport_front')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                    <div id="passport_front_preview" class="upload-preview"></div>
                    <div id="passport_front_progress" class="upload-progress">
                        <div class="progress-bar"></div>
                    </div>
                </div>
                <!-- Загрузите ВУ (Водительский удостоверения) - Лицевая сторона -->
                <div class="form-group upload-group">
                    <div class="upload-label-wrapper">
                        <div class="upload-label">
                            Загрузите ВУ<br>(Водительский удостоверения)<br>Лицевая сторона
                        </div>
                        <label class="upload-box">
                            <input type="file" name="license_front" class="file-input" required
                                data-preview-target="#license_front_preview"
                                data-progress-target="#license_front_progress" />
                            <span class="upload-icon">+</span>
                        </label>
                    </div>
                    @error('license_front')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                    <div id="license_front_preview" class="upload-preview"></div>
                    <div id="license_front_progress" class="upload-progress">
                        <div class="progress-bar"></div>
                    </div>
                </div>
            </div>

            <div class="form-column">
                <!-- ВУ (№ Удостоверения) с маской, максимум 9 символов, заглавные -->
                <div class="form-group">
                    <label for="license-num">ВУ (№ Удостоверения)</label>
                    <input type="text" id="license-num" name="license_number" placeholder="012345678" autocomplete="off"
                        maxlength="9" style="text-transform: uppercase;" value="{{ old('license_number') }}" required />
                    @error('license_number')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Дата выдачи -->
                <div class="form-group">
                    <label for="issue-date">Дата выдачи</label>
                    <input type="text" id="issue-date" name="license_issue_date" placeholder="23.06.2024"
                        autocomplete="off" value="{{ old('license_issue_date') }}" required />
                    @error('license_issue_date')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Срок действия -->
                <div class="form-group">
                    <label for="expiry-date">Срок действия</label>
                    <input type="text" id="expiry-date" name="license_expiry_date" placeholder="23.06.2024"
                        autocomplete="off" value="{{ old('license_expiry_date') }}" required />
                    @error('license_expiry_date')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Новое поле: Дата регистрации -->
                <div class="form-group">
                    <label for="registration-date">Дата регистрации</label>
                    <input type="text" id="registration-date" name="registration_date" readonly
                        value="{{ old('registration_date', date('d.m.Y')) }}" />
                    @error('registration_date')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Загрузите ID (Паспорт) - Задняя сторона -->
                <div class="form-group upload-group">
                    <div class="upload-label-wrapper">
                        <div class="upload-label">
                            Загрузите ID<br>(Персональный Паспорт)<br>Задняя сторона
                        </div>
                        <label class="upload-box">
                            <input type="file" name="passport_back" class="file-input" required
                                data-preview-target="#passport_back_preview"
                                data-progress-target="#passport_back_progress" />
                            <span class="upload-icon">+</span>
                        </label>
                    </div>
                    @error('passport_back')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                    <div id="passport_back_preview" class="upload-preview"></div>
                    <div id="passport_back_progress" class="upload-progress">
                        <div class="progress-bar"></div>
                    </div>
                </div>
                <!-- Загрузите ВУ (Водительский удостоверения) - Задняя сторона -->
                <div class="form-group upload-group">
                    <div class="upload-label-wrapper">
                        <div class="upload-label">
                            Загрузите ВУ<br>(Водительский удостоверения)<br>Задняя сторона
                        </div>
                        <label class="upload-box">
                            <input type="file" name="license_back" class="file-input" required
                                data-preview-target="#license_back_preview"
                                data-progress-target="#license_back_progress" />
                            <span class="upload-icon">+</span>
                        </label>
                    </div>
                    @error('license_back')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                    <div id="license_back_preview" class="upload-preview"></div>
                    <div id="license_back_progress" class="upload-progress">
                        <div class="progress-bar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <button type="submit" class="main__btn-green">Далее</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Подключаем jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
$(document).ready(function() {
    // Инициализация datepicker
    $('#date_of_birth, #license_issue_date, #license_expiry_date').mask('00.00.0000');
    
    // Инициализация маски для телефона в киргизском формате
    $('#phone').mask('+996 000 00 00 00');

    // Маска для ВУ (№ Удостоверения) - максимум 9 символов, заглавные
    $('#license-num').mask('AAAAAAAAA', {
        translation: {
            'A': {
                pattern: /[A-Z0-9]/,
                optional: true
            }
        },
        placeholder: "_________"
    }).on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Функция для предпросмотра и прогресса загрузки файла
    function handleFileInputChange(input) {
        var file = input.files[0];
        if (file) {
            var previewTarget = $(input).data('preview-target');
            var progressTarget = $(input).data('progress-target');
            var reader = new FileReader();

            reader.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percentLoaded = Math.round((e.loaded / e.total) * 100);
                    $(progressTarget + " .progress-bar").css("width", percentLoaded + "%").text(
                        percentLoaded + "%");
                }
            };

            reader.onload = function(e) {
                $(previewTarget).html('<img src="' + e.target.result + '" alt="Preview">');
            };

            reader.readAsDataURL(file);
        }
    }

    $('.file-input').on('change', function() {
        handleFileInputChange(this);
    });
});
</script>
@endpush