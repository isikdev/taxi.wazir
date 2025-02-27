<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация водителя - Шаг 3 | Wazir.kg</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
        <style>
            /* Дополнительные стили, если нужны */
            body { padding: 100px 0; }
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
                                Укажите имя и фамилию
                            </h1>
                        </div>
                        <div class="register__form register__auth">
                            <form action="{{ route('driver.auth.processStep3') }}" method="POST" id="registration-form" style="width: 100%;">
                                @csrf
                                <input type="text" name="first_name" placeholder="Имя" required>
                                <input type="text" name="last_name" placeholder="Фамилия" required>
                                <button type="submit" class="main__btn">Продолжить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Пример небольшого JS для активации кнопки при заполнении полей -->
        <script>
            $(document).ready(function(){
                $('#registration-form input').on('input', function(){
                    let allFilled = true;
                    $('#registration-form input[type="text"]').each(function(){
                        if($(this).val().trim() === ''){
                            allFilled = false;
                        }
                    });
                    if(allFilled){
                        $('.main__btn').addClass('main__btn-active');
                    } else {
                        $('.main__btn').removeClass('main__btn-active');
                    }
                });
            });
        </script>
    </body>
</html>
