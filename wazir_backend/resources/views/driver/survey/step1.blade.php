<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета водителя - Шаг 1 | Wazir.kg</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <style>
    .register {
        min-height: calc(100vh - header-height);
    }

    .register__content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100%;
    }

    .button-wrapper {
        margin-top: auto;
        padding-bottom: 20px;
    }

    .main__btn-active {
        width: 100%;
    }
    </style>
</head>

<body>
    <header>
        <div class="brand">
            <div class="container">
                <div class="brand__content">
                    <div class="logo">
                        <a href="{{ route('driver.survey.step1') }}"> <img
                                src="{{ asset('assets/img/driver/logo.png') }}" alt="logo">
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
                            Заполнение анкеты
                        </h1>
                        <p style="font-size: 14px; font-weight: 500; text-align: center;">Выберите
                            удобные для вас условия работы и
                            укажите данные о себе. Начнем?</p>
                    </div>
                    <div class="button-wrapper">
                        <form action="{{ route('driver.survey.processStep1') }}" method="POST">
                            @csrf
                            <button type="submit" class="main__btn-active">Начать</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>