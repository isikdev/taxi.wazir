<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация водителя - Шаг 2 | Wazir.kg</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
        <style>
            .iti { width: 100% !important; }
            body { padding: 100px 0; }
            /* Пример стиля для невалидной формы */
            .invalid input {
                border: 1px solid red;
            }
            .invalid-text {
                color: red;
                font-weight: bold;
            }
            .main__btn-active {
                opacity: 1;
                cursor: pointer;
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
                                Введите код из смс. Мы отправили его на номер 
                                <span class="active-phone-number"></span>
                            </h1>
                        </div>
                        <div class="register__form">
                            <form action="{{ route('driver.auth.processStep2') }}" method="POST" id="sms-form">
                                @csrf
                                <div class="sms-code-wrapper">
                                    <input type="text" maxlength="1" class="sms-code">
                                    <input type="text" maxlength="1" class="sms-code">
                                    <input type="text" maxlength="1" class="sms-code">
                                    <input type="text" maxlength="1" class="sms-code">
                                </div>
                                <button type="submit" class="main__btn">Подтвердить</button>
                            </form>
                            <p class="timer-sms">Код не пришел</p>
                            <p class="invalid-text" style="display: none;">Неверный код</p>
                            <a class="resend-sms" href="#" style="display: none;">Отправить код повторно</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            // Отобразить номер телефона из URL
            const urlParams = new URLSearchParams(window.location.search);
            const phoneNumber = urlParams.get('phone');
            if (phoneNumber) {
                $('.active-phone-number').text(decodeURIComponent(phoneNumber));
            }
            
            const $smsInputs = $('.sms-code');
            const $form = $('#sms-form');

            // Обработка ввода: оставляем только цифры и автопереход к следующему полю
            $smsInputs.on('input', function() {
                this.value = this.value.replace(/[^\d]/g, '');
                if (this.value.length === 1) {
                    $(this).next('.sms-code').focus();
                }
                
                // Если все поля заполнены, объединяем код
                let combinedCode = '';
                $smsInputs.each(function() {
                    combinedCode += $(this).val();
                });
                if (combinedCode.length === 4) {
                    if (combinedCode === '1111') {
                        $form.removeClass('invalid');
                        $('.invalid-text').hide();
                        $('.resend-sms').hide();
                        // Если код верный, перенаправляем на шаг 3
                        window.location.href = "{{ route('driver.auth.step3') }}";
                    } else {
                        $form.addClass('invalid');
                        $('.invalid-text').show();
                        $('.resend-sms').show();
                    }
                }
            });

            // Обработка клавиши Backspace для переключения фокуса
            $smsInputs.on('keydown', function(e) {
                if (e.keyCode === 8 && !this.value) {
                    $(this).prev('.sms-code').focus();
                }
            });

            // Таймер для повторной отправки кода
            let timeLeft = 59;
            const timerElement = $('.timer-sms');
            const resendLink = $('.resend-sms');
            const invalidText = $('.invalid-text');

            function startTimer() {
                const timer = setInterval(function() {
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        timerElement.hide();
                        resendLink.show();
                    } else {
                        timerElement.text(`Код не пришел (0:${timeLeft.toString().padStart(2, '0')})`);
                        timeLeft--;
                    }
                }, 1000);
            }
            startTimer();

            // Обработка клика по ссылке "Отправить код повторно"
            resendLink.on('click', function(e) {
                e.preventDefault();
                timeLeft = 59;
                $(this).hide();
                timerElement.show();
                $form.removeClass('invalid');
                $smsInputs.val('');
                $smsInputs.first().focus();
                startTimer();
            });
        });
        </script>
    </body>
</html>
