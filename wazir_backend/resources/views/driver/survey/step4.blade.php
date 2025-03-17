<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .selected-option {
        background-color: #f0f9ff;
        border-color: #3498db;
    }

    .options-list {
        display: none;
        position: absolute;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ccc;
        z-index: 10;
        border-radius: 0 0 5px 5px;
    }

    .options-list.active {
        display: block;
    }

    .option-item {
        padding: 8px 12px;
        cursor: pointer;
    }

    .option-item:hover {
        background-color: #f5f5f5;
    }

    .custom-select {
        position: relative;
    }

    .form-input-wrapper {
        position: relative;
    }

    .arrow-select {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Новый стиль для переключателей Да/Нет */
    .toggle-buttons {
        display: flex;
        margin-top: 10px;
        width: 100%;
        gap: 10px;
    }

    .toggle-button {
        position: relative;
        flex: 1;
    }

    .toggle-button input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
    }

    .toggle-button label {
        display: block;
        background-color: #f1f1f1;
        color: #666;
        text-align: center;
        padding: 12px 0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        width: 100%;
    }

    .toggle-button input[type="radio"]:checked+label {
        background-color: #606060;
        color: white;
    }

    /* Модальное окно для селекторов */
    .modal-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        width: 90%;
        max-width: 400px;
        border-radius: 10px;
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .close {
        font-size: 24px;
        cursor: pointer;
        color: #999;
    }

    .search-container {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }

    .search-container input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .options-container {
        overflow-y: auto;
        max-height: 60vh;
    }

    .option {
        padding: 12px 15px;
        border-bottom: 1px solid #f5f5f5;
        cursor: pointer;
    }

    .option:hover {
        background-color: #f9f9f9;
    }
    </style>
</head>

<body>
    <main>
        <header>
            <div class="back">
                <div class="container">
                    <div class="back__content">
                        <a href="{{ route('driver.survey.step3') }}"><img
                                src="{{ asset('assets/img/driver/ico/back.svg') }}" alt="back"></a>
                    </div>
                </div>
            </div>
        </header>
        <section class="survey-3">
            <div class="container">
                <div class="survey__content">
                    <h1 class="title-left">
                        Заполните анкету
                    </h1>
                    <div class="survey__profile-wrapper">
                        <div class="survey__profile">
                            <div class="survey__profile-item-active">
                                <img src="{{ asset('assets/img/driver/ico/check.svg') }}" alt="check">
                            </div>
                            <p>Про вас</p>
                        </div>
                        <div class="survey__profile">
                            <div class="survey__profile-item-active">
                                2
                            </div>
                            <p>Про авто</p>
                        </div>
                        <div class="survey__profile">
                            <div class="survey__profile-item">
                                3
                            </div>
                            <p>Условия</p>
                        </div>
                    </div>
                    <div class="register__form register__auth">
                        <form action="{{ route('driver.survey.processStep4') }}" method="POST">
                            @csrf

                            @if ($errors->any())
                            <div
                                style="background-color: #ffdddd; border: 1px solid #ff0000; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                                <strong>Ошибки при заполнении формы:</strong>
                                <ul style="margin-top: 5px; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if(request()->has('redirect_to_complete'))
                            <input type="hidden" name="redirect_to_complete" value="1">
                            @endif

                            <input type="hidden" name="car_brand" id="car_brand_hidden"
                                value="{{ old('car_brand', session('car_brand', '')) }}">
                            <input type="hidden" name="car_model" id="car_model_hidden"
                                value="{{ old('car_model', session('car_model', '')) }}">
                            <input type="hidden" name="car_color" id="car_color_hidden"
                                value="{{ old('car_color', session('car_color', '')) }}">
                            <input type="hidden" name="car_year" id="car_year_hidden"
                                value="{{ old('car_year', session('car_year', '')) }}">

                            <div class="form-group">
                                <label for="mark">Выберите марку</label>
                                <div class="custom-select">
                                    <div class="form-input-wrapper">
                                        <input type="text" id="mark" class="form-input" readonly
                                            placeholder="Выберите марку"
                                            value="{{ old('car_brand', session('car_brand', '')) }}">
                                        <span class="arrow-select">›</span>
                                    </div>
                                    <div class="options-list" id="brands-list">
                                        @foreach($brands as $brand)
                                        <div class="option-item" data-value="{{ $brand }}">{{ $brand }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="model">Выберите модель</label>
                                <div class="custom-select">
                                    <div class="form-input-wrapper">
                                        <input type="text" id="model" class="form-input" readonly
                                            placeholder="Выберите модель"
                                            value="{{ old('car_model', session('car_model', '')) }}">
                                        <span class="arrow-select">›</span>
                                    </div>
                                    <div class="options-list" id="models-list">
                                        @foreach($models as $model)
                                        <div class="option-item" data-value="{{ $model }}">{{ $model }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="color">Выберите цвет</label>
                                <div class="custom-select">
                                    <div class="form-input-wrapper">
                                        <input type="text" id="color" class="form-input" readonly
                                            placeholder="Выберите цвет"
                                            value="{{ old('car_color', session('car_color', '')) }}">
                                        <span class="arrow-select">›</span>
                                    </div>
                                    <div class="options-list" id="colors-list">
                                        @foreach($colors as $color)
                                        <div class="option-item" data-value="{{ $color }}">{{ $color }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="year">Выберите год выпуска</label>
                                <div class="custom-select">
                                    <div class="form-input-wrapper">
                                        <input type="text" id="year" class="form-input" readonly
                                            placeholder="Выберите год"
                                            value="{{ old('car_year', session('car_year', '')) }}">
                                        <span class="arrow-select">›</span>
                                    </div>
                                    <div class="options-list" id="years-list">
                                        @foreach($years as $year)
                                        <div class="option-item" data-value="{{ $year }}">{{ $year }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="number">Введите гос.номер</label>
                                <input type="text" id="number" name="license_plate" class="form-input"
                                    placeholder="00 KG 000 AAA"
                                    style="letter-spacing: 1px; font-size: 16px; text-align: left; background-color: #fcfcfc; text-transform: uppercase;"
                                    value="{{ old('license_plate', session('license_plate', '')) }}">
                                <small style="display: block; text-align: left; margin-top: 5px; color: #777;">
                                    Формат: 00 KG 000 AAA
                                </small>
                            </div>

                            <!-- Поле VIN с улучшенной маской -->
                            <div class="form-group">
                                <label class="form-label">Введите VIN</label>
                                <div class="form-input-with-arrow">
                                    <input type="text" id="vin_input" name="vin" placeholder="VIN номер (17 символов)"
                                        maxlength="17" style="text-transform: uppercase; letter-spacing: 1px;"
                                        value="{{ old('vin', session('vin', '')) }}">
                                </div>
                                <small style="display: block; text-align: left; margin-top: 5px; color: #777;">
                                    17 символов: цифры и буквы латиницей (кроме I, O, Q)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Введите номер кузова</label>
                                <div class="form-input-with-arrow">
                                    <input type="text" id="body_number_input" name="body_number"
                                        placeholder="Номер кузова (17 символов)" maxlength="17"
                                        style="text-transform: uppercase; letter-spacing: 1px;"
                                        value="{{ old('body_number', session('body_number', '')) }}">
                                </div>
                                <small style="display: block; text-align: left; margin-top: 5px; color: #777;">
                                    17 символов: цифры и буквы латиницей (кроме I, O, Q)
                                </small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Введите СТС</label>
                                <div class="form-input-with-arrow">
                                    <input type="text" name="sts" placeholder="Свидетельство о регистрации ТС"
                                        value="{{ old('sts', session('sts', '')) }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Выберите КПП</label>
                                <div class="form-input-with-arrow custom-select">
                                    <input type="text" id="transmission" name="transmission" class="form-input" readonly
                                        placeholder="Выберите КПП"
                                        value="{{ old('transmission', session('transmission', '')) }}">
                                    <span class="arrow-select">›</span>
                                    <div class="options-list" id="transmission-list">
                                        <!-- Опции будут загружены из JSON -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Бустеры</label>
                                <div class="form-input-with-arrow custom-select">
                                    <input type="text" id="boosters" name="boosters" class="form-input" readonly
                                        placeholder="Выберите количество"
                                        value="{{ old('boosters', session('boosters', '')) }}">
                                    <span class="arrow-select">›</span>
                                    <div class="options-list" id="boosters-list">
                                        <!-- Опции будут загружены из JSON -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Тариф</label>
                                <div class="form-input-with-arrow custom-select">
                                    <input type="text" id="tariff" name="tariff" class="form-input" readonly
                                        placeholder="Выберите тариф" value="{{ old('tariff', session('tariff', '')) }}">
                                    <span class="arrow-select">›</span>
                                    <div class="options-list" id="tariff-list">
                                        <!-- Опции будут загружены из JSON -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Услуга</label>
                                <div class="form-input-with-arrow custom-select">
                                    <input type="text" id="service" name="service_type" class="form-input" readonly
                                        placeholder="Выберите услугу"
                                        value="{{ old('service_type', session('service_type', '')) }}">
                                    <span class="arrow-select">›</span>
                                    <div class="options-list" id="service-list">
                                        <!-- Опции будут загружены из JSON -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Категория</label>
                                <div class="form-input-with-arrow custom-select">
                                    <input type="text" id="category" name="category" class="form-input" readonly
                                        placeholder="Выберите категорию"
                                        value="{{ old('category', session('category', '')) }}">
                                    <span class="arrow-select">›</span>
                                    <div class="options-list" id="category-list">
                                        <!-- Опции будут загружены из JSON -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Детское кресло</label>
                                <div class="toggle-buttons">
                                    <div class="toggle-button">
                                        <input type="radio" id="child_seat_yes" name="child_seat" value="Да"
                                            {{ old('child_seat', session('child_seat')) === 'Да' ? 'checked' : '' }}>
                                        <label for="child_seat_yes">Да</label>
                                    </div>
                                    <div class="toggle-button">
                                        <input type="radio" id="child_seat_no" name="child_seat" value="Нет"
                                            {{ old('child_seat', session('child_seat')) === 'Нет' ? 'checked' : '' }}>
                                        <label for="child_seat_no">Нет</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Наклейка</label>
                                <div class="toggle-buttons">
                                    <div class="toggle-button">
                                        <input type="radio" id="nakleyka_yes" name="has_nakleyka" value="1"
                                            {{ old('has_nakleyka', session('has_nakleyka')) == '1' ? 'checked' : '' }}>
                                        <label for="nakleyka_yes">Да</label>
                                    </div>
                                    <div class="toggle-button">
                                        <input type="radio" id="nakleyka_no" name="has_nakleyka" value="0"
                                            {{ old('has_nakleyka', session('has_nakleyka')) == '0' ? 'checked' : '' }}>
                                        <label for="nakleyka_no">Нет</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Лайтбокс - Шашка</label>
                                <div class="toggle-buttons">
                                    <div class="toggle-button">
                                        <input type="radio" id="lightbox_yes" name="has_lightbox" value="1"
                                            {{ old('has_lightbox', session('has_lightbox')) == '1' ? 'checked' : '' }}>
                                        <label for="lightbox_yes">Да</label>
                                    </div>
                                    <div class="toggle-button">
                                        <input type="radio" id="lightbox_no" name="has_lightbox" value="0"
                                            {{ old('has_lightbox', session('has_lightbox')) == '0' ? 'checked' : '' }}>
                                        <label for="lightbox_no">Нет</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Парковая машина</label>
                                <div class="toggle-buttons">
                                    <div class="toggle-button">
                                        <input type="radio" id="parking_car_yes" name="parking_car" value="Да"
                                            {{ old('parking_car', session('parking_car')) === 'Да' ? 'checked' : '' }}>
                                        <label for="parking_car_yes">Да</label>
                                    </div>
                                    <div class="toggle-button">
                                        <input type="radio" id="parking_car_no" name="parking_car" value="Нет"
                                            {{ old('parking_car', session('parking_car')) === 'Нет' ? 'checked' : '' }}>
                                        <label for="parking_car_no">Нет</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="submit-btn"
                                class="main__btn {{ (old('car_brand', session('car_brand', '')) && 
                                                    old('car_model', session('car_model', '')) && 
                                                    old('car_color', session('car_color', '')) && 
                                                    old('car_year', session('car_year', '')) && 
                                                    old('license_plate', session('license_plate', '')) &&
                                                    old('vin', session('vin', '')) &&
                                                    old('body_number', session('body_number', '')) &&
                                                    old('sts', session('sts', '')) &&
                                                    old('transmission', session('transmission', '')) &&
                                                    old('boosters', session('boosters', '')) &&
                                                    old('tariff', session('tariff', '')) &&
                                                    old('service_type', session('service_type', '')) &&
                                                    old('category', session('category', '')) ) ? 'main__btn-active' : '' }}">
                                Продолжить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
    $(document).ready(function() {
        // МАСКА ДЛЯ ГОСНОМЕРА - СТРОГОЕ ОГРАНИЧЕНИЕ
        const $licenseInput = $('#number');

        // Маскированный ввод для госномера с фиксированным форматом и CAPS LOCK
        $licenseInput.mask('00 KG 000 AAA', {
            placeholder: '__ KG ___ ___',
            translation: {
                'A': {
                    pattern: /[A-Za-z]/,
                    transform: function(val) {
                        // Преобразование русских букв в латиницу и всегда в верхний регистр
                        const russianToLatin = {
                            'А': 'A',
                            'В': 'B',
                            'Е': 'E',
                            'К': 'K',
                            'М': 'M',
                            'Н': 'H',
                            'О': 'O',
                            'Р': 'P',
                            'С': 'C',
                            'Т': 'T',
                            'Х': 'X',
                            'а': 'A',
                            'в': 'B',
                            'е': 'E',
                            'к': 'K',
                            'м': 'M',
                            'н': 'H',
                            'о': 'O',
                            'р': 'P',
                            'с': 'C',
                            'т': 'T',
                            'х': 'X'
                        };
                        if (russianToLatin[val]) {
                            return russianToLatin[val];
                        }
                        return val.toUpperCase();
                    }
                },
                '0': {
                    pattern: /[0-9]/
                }
            }
        });

        // ОБРАБОТКА VIN-КОДА
        const $vinInput = $('#vin_input');

        // Обработка VIN-кода: только цифры и буквы (кроме I, O, Q), всегда в верхнем регистре
        $vinInput.on('input', function() {
            let value = $(this).val();

            // Преобразуем весь ввод в верхний регистр
            value = value.toUpperCase();

            // Удаляем недопустимые символы (I, O, Q и любые не-буквы, не-цифры)
            value = value.replace(/[^A-HJ-NPR-Z0-9]/g, '');

            // Ограничиваем до 17 символов
            if (value.length > 17) {
                value = value.substr(0, 17);
            }

            // Устанавливаем обработанное значение
            $(this).val(value);

            // Визуально отмечаем поле, если длина не равна 17
            if (value.length === 17) {
                $(this).css('border-color', '#4CAF50');
            } else {
                $(this).css('border-color', '');
            }
        });

        // Инициализация для VIN, если есть начальное значение
        if ($vinInput.val()) {
            $vinInput.trigger('input');
        }

        // ОБРАБОТКА НОМЕРА КУЗОВА - аналогично VIN
        const $bodyNumberInput = $('#body_number_input');

        // Обработка номера кузова: только цифры и буквы (кроме I, O, Q), всегда в верхнем регистре
        $bodyNumberInput.on('input', function() {
            let value = $(this).val();

            // Преобразуем весь ввод в верхний регистр
            value = value.toUpperCase();

            // Удаляем недопустимые символы (I, O, Q и любые не-буквы, не-цифры)
            value = value.replace(/[^A-HJ-NPR-Z0-9]/g, '');

            // Ограничиваем до 17 символов
            if (value.length > 17) {
                value = value.substr(0, 17);
            }

            // Устанавливаем обработанное значение
            $(this).val(value);

            // Визуально отмечаем поле, если длина не равна 17
            if (value.length === 17) {
                $(this).css('border-color', '#4CAF50');
            } else {
                $(this).css('border-color', '');
            }
        });

        // Инициализация для номера кузова, если есть начальное значение
        if ($bodyNumberInput.val()) {
            $bodyNumberInput.trigger('input');
        }

        // Форматирование текущего значения, если оно есть
        const initialValue = $licenseInput.val();
        if (initialValue && initialValue.trim() !== '') {
            // Извлекаем только цифры и буквы
            const digits = initialValue.replace(/[^0-9]/g, '');
            let letters = initialValue.replace(/[^A-Za-z]/g, '').toUpperCase();

            // Конвертируем русские буквы
            const russianToLatin = {
                'А': 'A',
                'В': 'B',
                'Е': 'E',
                'К': 'K',
                'М': 'M',
                'Н': 'H',
                'О': 'O',
                'Р': 'P',
                'С': 'C',
                'Т': 'T',
                'Х': 'X'
            };
            letters = letters.split('').map(char => russianToLatin[char] || char).join('');

            // Формируем значение в правильном формате
            let formatted = '';
            if (digits.length >= 2) {
                formatted += digits.substring(0, 2);
                formatted += ' KG ';

                if (digits.length > 2) {
                    formatted += digits.substring(2, Math.min(5, digits.length));

                    // Добавляем нули, если цифр меньше 3
                    while (formatted.length < 8) {
                        formatted += '0';
                    }
                }

                formatted += ' ';

                if (letters.length > 0) {
                    formatted += letters.substring(0, Math.min(3, letters.length));

                    // Добавляем "A", если букв меньше 3
                    while (formatted.length < 12) {
                        formatted += 'A';
                    }
                } else {
                    formatted += 'AAA';
                }

                $licenseInput.val(formatted);
            }
        }

        // Функционал выпадающих списков
        $('.custom-select input').on('click', function() {
            const parent = $(this).closest('.custom-select');
            const list = parent.find('.options-list');

            $('.options-list').not(list).removeClass('active');
            list.toggleClass('active');

            // Если нужно загрузить данные
            loadOptionsIfNeeded(this.id);
        });

        // Функция для загрузки опций из JSON-файла
        function loadOptionsIfNeeded(inputId) {
            const jsonKey = inputId === 'transmission' ? 'transmissions' :
                inputId === 'boosters' ? 'boosters' :
                inputId === 'tariff' ? 'tariffs' :
                inputId === 'service' ? 'services' :
                inputId === 'category' ? 'categories' : null;

            if (!jsonKey) return;

            const listId = inputId + '-list';
            const list = $('#' + listId);

            // Если список еще не загружен
            if (list.children().length === 0) {
                // Загружаем данные из JSON
                $.getJSON('/js/car_selects.json', function(data) {
                    const options = data[jsonKey] || [];
                    let html = '';

                    options.forEach(function(option) {
                        html +=
                            `<div class="option-item" data-value="${option}">${option}</div>`;
                    });

                    list.html(html);

                    // Добавляем обработчики событий для новых элементов
                    list.find('.option-item').on('click', function() {
                        const value = $(this).data('value');
                        $('#' + inputId).val(value);
                        list.removeClass('active');
                        checkFormCompletion();
                    });
                });
            }
        }

        // Обработка выбора опции для существующих списков
        $(document).on('click', '.option-item', function() {
            const value = $(this).data('value');
            const parentList = $(this).closest('.options-list');
            const inputId = parentList.attr('id') === 'brands-list' ? 'mark' :
                parentList.attr('id') === 'models-list' ? 'model' :
                parentList.attr('id') === 'colors-list' ? 'color' :
                parentList.attr('id') === 'years-list' ? 'year' : null;

            if (inputId) {
                const hiddenInputId = inputId === 'mark' ? 'car_brand_hidden' :
                    inputId === 'model' ? 'car_model_hidden' :
                    inputId === 'color' ? 'car_color_hidden' : 'car_year_hidden';

                $('#' + inputId).val(value);
                $('#' + hiddenInputId).val(value);
            } else {
                // Для новых селекторов
                const customSelect = $(this).closest('.custom-select');
                const input = customSelect.find('input[type="text"]');
                input.val(value);
            }

            parentList.removeClass('active');
            checkFormCompletion();
        });

        // Закрытие списков при клике вне них
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.custom-select').length) {
                $('.options-list').removeClass('active');
            }
        });

        // Проверка заполнения формы
        function checkFormCompletion() {
            const brand = $('#car_brand_hidden').val();
            const model = $('#car_model_hidden').val();
            const color = $('#car_color_hidden').val();
            const year = $('#car_year_hidden').val();
            const plate = $('#number').val();
            const vin = $('input[name="vin"]').val();
            const bodyNumber = $('input[name="body_number"]').val();
            const sts = $('input[name="sts"]').val();
            const transmission = $('#transmission').val();
            const boosters = $('#boosters').val();
            const tariff = $('#tariff').val();
            const service = $('#service').val();
            const category = $('#category').val();

            if (
                brand && model && color && year && plate &&
                vin && vin.length === 17 && // Проверяем что VIN имеет ровно 17 символов
                bodyNumber && bodyNumber.length === 17 && // Проверяем что номер кузова имеет ровно 17 символов
                sts.trim() !== '' &&
                transmission && transmission !== 'Выберите КПП' &&
                boosters && boosters !== 'Выберите количество' &&
                tariff && tariff !== 'Выберите тариф' &&
                service && service !== 'Выберите услугу' &&
                category && category !== 'Выберите категорию'
            ) {
                $('#submit-btn').addClass('main__btn-active');
            } else {
                $('#submit-btn').removeClass('main__btn-active');
            }
        }

        // Обработчики изменения полей
        $('input[type="text"], input[type="radio"]').on('input change', checkFormCompletion);

        // Инициализация переключателей
        $('input[type="radio"]').change(function() {
            const name = $(this).attr('name');
            if ($(this).is(':checked')) {
                $('.toggle-buttons input[name="' + name + '"]').not(this).prop('checked', false);
            }
            checkFormCompletion();
        });

        // Начальная проверка формы
        checkFormCompletion();

        // Инициализация значений для селекторов
        function initSelector(inputId) {
            const value = $('#' + inputId).val();
            if (value && value !== '' && value !== 'Выберите КПП' && value !== 'Выберите количество' &&
                value !== 'Выберите тариф' && value !== 'Выберите услугу' && value !== 'Выберите категорию') {
                loadOptionsIfNeeded(inputId);
            }
        }

        // Инициализация для всех селекторов
        initSelector('transmission');
        initSelector('boosters');
        initSelector('tariff');
        initSelector('service');
        initSelector('category');
    });
    </script>
</body>

</html>