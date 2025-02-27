<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>

<body>
    <main>
        <header>
            <div class="back">
                <div class="container">
                    <div class="back__content">
                        <a href="{{ route('driver.survey.step4') }}"><img
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
                                <img src="{{ asset('assets/img/driver/ico/check.svg') }}" alt="check">
                            </div>
                            <p>Про авто</p>
                        </div>
                        <div class="survey__profile">
                            <div class="survey__profile-item-active">
                                3
                            </div>
                            <p>Условия</p>
                        </div>
                    </div>
                    <form action="{{ route('driver.survey.processStep5') }}" method="POST">
                        @csrf
                        <div class="survey__profile-info">
                            <div class="survery__profile-info-title">
                                <p>В городе {{ session('city') }} можно выполнять
                                    заказы только через парк</p>
                            </div>
                            <div class="survey__profile-info-wraper">
                                <div class="survey__profile-info-title">
                                    <h4>Условия парка</h4>
                                </div>
                                <div class="survey__profile-info-item">
                                    <span>1</span>
                                    <p>Парк берет процент с каждой поездки</p>
                                </div>
                                <div class="survey__profile-info-item">
                                    <span>2</span>
                                    <p>Парк выплачивает деньги за заказы</p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="main__btn-active" style="margin: 30px auto;">Продолжить</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>

</html>