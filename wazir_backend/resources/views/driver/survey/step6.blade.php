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
                        <a href="{{ route('driver.survey.step5') }}"><img
                                src="{{ asset('assets/img/driver/ico/back.svg') }}"
                                alt="back"></a>
                    </div>
                </div>
            </div>
        </header>
        <section class="survey-3">
            <div class="container">
                <div class="survey__content">
                    <h1 class="title-left">
                        Выберите парк
                    </h1>
                    <div class="survey__park">
                        @foreach($taxiParks as $park)
                        <div class="survey__park-item">
                            @if(request()->has('redirect_to_complete'))
                            <a href="{{ route('driver.survey.processStep6', ['park_id' => $park['id'], 'redirect_to_complete' => 1]) }}">{{ $park['name'] }}</a>
                            @else
                            <a href="{{ route('driver.survey.processStep6', ['park_id' => $park['id']]) }}">{{ $park['name'] }}</a>
                            @endif
                            <img src="{{ asset('assets/img/driver/ico/next.svg') }}"
                                alt="next">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>