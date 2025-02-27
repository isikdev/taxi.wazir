<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация водитель Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <style>
    .iti {
        width: 100% !important;
    }

    body {
        padding: 100px 0;
    }
    </style>
</head>

<body>
    <header>
        <div class="brand">
            <div class="container">
                <div class="brand__content">
                    <div class="logo">
                        <a href="{{ route('driver.auth.step1') }}">
                            <img src="{{ asset('assets/img/driver/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="register">
            <div class="container">
                <div class="register__content">
                    <div class="register__intro">
                        <h1 class="title">
                            Регистрация по номеру телефона
                        </h1>
                    </div>
                    <div class="register__tabs">
                        <div class="tabs">
                            <button><a href="https://wazir.kg/client/auth/1.html"></a>
                                Пользователь</button>
                            <button class="active">Водитель</button>
                        </div>
                    </div>
                    <div class="register__form">
                        <form action="{{ route('driver.auth.processStep1') }}" method="POST" style="width: 100%;"
                            id="phoneForm">
                            @csrf
                            <input type="tel" id="phone" class="phone" name="phone" required
                                style="padding-right: 60px; width: 100%;">
                            <button type="submit" class="main__btn" disabled>Отправить</button>
                        </form>
                        <div class="register__block">
                            <a href="#">
                                Нажимая кнопку вы соглашаетесь с условиями
                                <span> Пользовательского соглашения</span>
                                и
                                <span> Политики
                                    конфенденциальносит</span>
                            </a>
                        </div>
                        <div class="register__block">
                            <p>Уже есть аккаунт?
                                <a href="#">Войти</a>
                            </p>
                        </div>
                        <div class="register__block register__block-footer">
                            <p>Центр обработки, тех. поддержка, авторске
                                права</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
    $(document).ready(function() {
        // Инициализация телефонного ввода
        var phoneInput = document.querySelector("#phone");
        var iti = window.intlTelInput(phoneInput, {
            initialCountry: "kg", // По умолчанию Киргизия
            preferredCountries: ["kg", "ru", "kz"],
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // Применяем маску к телефону - киргизский формат: XXX XXX XXX
        $('#phone').mask('000 000 000', {
            placeholder: "XXX XXX XXX"
        });

        // Проверка валидности телефона при вводе и изменении фокуса
        $('#phone').on('input blur', function() {
            validatePhone();
        });

        function validatePhone() {
            var submitButton = $(".main__btn");
            // Получаем только цифры из введенного номера
            var phoneDigits = $('#phone').val().replace(/[^0-9]/g, '');

            // Активируем кнопку, если введено не менее 8 цифр (достаточно для распознавания номера)
            if (phoneDigits.length >= 8) {
                submitButton.addClass('main__btn-active');
                submitButton.prop('disabled', false);
            } else {
                submitButton.removeClass('main__btn-active');
                submitButton.prop('disabled', true);
            }
        }

        // Добавляем обработчик отправки формы для форматирования номера
        $('#phoneForm').on('submit', function(e) {
            // Получаем номер с кодом страны
            var fullNumber = iti.getNumber();

            // Заменяем значение в поле перед отправкой
            phoneInput.value = fullNumber;

            // Проверяем, что это киргизский номер, но не блокируем отправку
            if (!fullNumber.startsWith('+996')) {
                // Предупреждаем пользователя
                if (!confirm(
                        'Введенный номер не является киргизским (+996). Продолжить с этим номером?')) {
                    e.preventDefault(); // Отменяем отправку только если пользователь отказался
                    return false;
                }
            }
        });
    });
    </script>
</body>

</html>