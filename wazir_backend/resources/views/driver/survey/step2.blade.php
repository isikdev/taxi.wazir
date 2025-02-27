<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .survey__cities-list.active {
        overflow: scroll;
    }
    </style>
</head>

<body>
    <main>
        <header>
            <div class="back">
                <div class="container">
                    <div class="back__content">
                        <a href="{{ route('driver.survey.step1') }}"><img
                                src="{{ asset('assets/img/driver/ico/back.svg') }}" alt="back"></a>
                    </div>
                </div>
            </div>
        </header>
        <section class="survey">
            <div class="container">
                <div class="survey__content">
                    <h1 class="title-left">
                        Заполните анкету
                    </h1>
                    <div class="survey__profile-wrapper">
                        <div class="survey__profile">
                            <div class="survey__profile-item-active">
                                1
                            </div>
                            <p>Про вас</p>
                        </div>
                        <div class="survey__profile">
                            <div class="survey__profile-item">
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

                    <form action="{{ route('driver.survey.processStep2') }}" method="POST" id="cityForm">
                        @csrf
                        <input type="hidden" name="city" id="selectedCity"
                            value="{{ old('city', session('city', '')) }}">

                        <div class="survey__city-input">
                            <label>Укажите город</label>
                            <div class="survey__input-wrapper">
                                @if(old('city', session('city')))
                                <div class="survey__selected-city">
                                    <span>{{ old('city', session('city')) }}</span>
                                    <img src="{{ asset('assets/img/driver/ico/cancel.svg') }}" alt="cancel"
                                        class="cancel-icon">
                                </div>
                                @else
                                <input type="text" readonly placeholder="В каком городе вы хотите работать">
                                <span class="arrow-icon">></span>
                                @endif
                            </div>
                        </div>

                        <div class="survey__cities-list">
                            <div class="survey__search">
                                <input type="text" placeholder="Поиск">
                            </div>
                            <div class="survey__cities">
                                @foreach($cities as $city)
                                <div class="city-item" data-city="{{ $city }}">{{ $city }}</div>
                                @endforeach
                            </div>
                            <button type="button" class="main__btn confirm-city-btn"
                                style="margin: 30px auto;">Подтвердить</button>
                        </div>

                        <div class="city-info"
                            style="{{ old('city', session('city')) ? 'display: block;' : 'display: none;' }}">
                            <p class="available-options">
                                @if(old('city', session('city')))
                                В городе {{ old('city', session('city')) }} доступны следующие варианты:
                                @endif
                            </p>
                            <div class="driver-info">
                                <h3>Водитель</h3>
                                <p>На своем или арендованном автомобиле сможете помогать пассажирам добираться из одной
                                    точки в другую. А еще доставлять посылки и грузы до 20 килограмм, если в вашем
                                    городе
                                    доступен соответствующи тариф.</p>
                            </div>
                        </div>

                        @if(old('city', session('city')))
                        <button type="submit" class="main__btn main__btn-active"
                            style="margin: 50px auto;">Продолжить</button>
                        @endif
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        function bindCityEvents() {
            $('.city-item').on('click', function() {
                $('.city-item').removeClass('selected');
                $(this).addClass('selected');
                $('.confirm-city-btn').addClass('main__btn-active');

                // Автоматически выполняем действия как при нажатии на "Подтвердить"
                const cityName = $(this).text();
                $('#selectedCity').val(cityName);

                $('.survey__input-wrapper').html(`
                        <div class="survey__selected-city">
                            <span>${cityName}</span>
                            <img src="{{ asset('assets/img/driver/ico/cancel.svg') }}" alt="cancel" class="cancel-icon">
                        </div>
                    `);
                $('.survey__cities-list').removeClass('active').fadeOut(300);

                $('.city-info').show();
                $('.available-options').text(`В городе ${cityName} доступны следующие варианты:`);

                // Добавляем кнопку "Продолжить" если её ещё нет
                if ($('.survey__content .main__btn-active[type="submit"]').length === 0) {
                    $('#cityForm').append(
                        '<button type="submit" class="main__btn main__btn-active" style="margin: 50px auto;">Продолжить</button>'
                    );
                }
            });

            // Оставляем существующий обработчик для кнопки "Подтвердить" как запасной вариант
            $('.confirm-city-btn').on('click', function() {
                if ($('.city-item.selected').length) {
                    const cityName = $('.city-item.selected').text();
                    $('#selectedCity').val(cityName);

                    $('.survey__input-wrapper').html(`
                            <div class="survey__selected-city">
                                <span>${cityName}</span>
                                <img src="{{ asset('assets/img/driver/ico/cancel.svg') }}" alt="cancel" class="cancel-icon">
                            </div>
                        `);
                    $('.survey__cities-list').removeClass('active').fadeOut(300);

                    $('.city-info').show();
                    $('.available-options').text(`В городе ${cityName} доступны следующие варианты:`);

                    // Добавляем кнопку "Продолжить" если её ещё нет
                    if ($('.survey__content .main__btn-active[type="submit"]').length === 0) {
                        $('#cityForm').append(
                            '<button type="submit" class="main__btn main__btn-active" style="margin: 50px auto;">Продолжить</button>'
                        );
                    }
                }
            });

            $('.survey__search input').on('input', function() {
                const searchText = $(this).val().toLowerCase();
                $('.city-item').each(function() {
                    const cityText = $(this).text().toLowerCase();
                    $(this).toggle(cityText.includes(searchText));
                });
            });
        }

        $('.survey__input-wrapper').on('click', function() {
            $('.survey__cities-list').show().addClass('active');
        });

        bindCityEvents();

        $(document).on('click', '.cancel-icon', function(e) {
            e.stopPropagation();
            $('#selectedCity').val('');

            $('.survey__input-wrapper').html(`
                        <input type="text" readonly placeholder="В каком городе вы хотите работать">
                        <span class="arrow-icon">></span>
                    `);

            $('.survey__cities-list').show().addClass('active');
            $('.city-info').hide();
            $('.survey__content .main__btn-active[type="submit"]').remove();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.survey__cities-list').length &&
                !$(e.target).closest('.survey__input-wrapper').length) {
                $('.survey__cities-list').removeClass('active').fadeOut(300);
            }
        });

        // Подсветить уже выбранный город, если он есть
        const savedCity = $('#selectedCity').val();
        if (savedCity) {
            $(`.city-item[data-city="${savedCity}"]`).addClass('selected');
        }
    });
    </script>
</body>

</html>