<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Заполнение анкеты Wazir.kg</title>
        <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <style>
            .terms-container {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-top: 20px;
            }
            
            .terms-container h2 {
                font-size: 18px;
                margin-bottom: 15px;
                color: #333;
            }
            
            .terms-list {
                list-style-type: none;
                padding: 0;
            }
            
            .terms-list li {
                margin-bottom: 15px;
                padding-left: 25px;
                position: relative;
            }
            
            .terms-list li:before {
                content: '✓';
                position: absolute;
                left: 0;
                color: #4caf50;
                font-weight: bold;
            }
            
            .main__btn-active {
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <main>
            <header>
                <div class="back">
                    <div class="container">
                        <div class="back__content">
                            <a href="{{ route('driver.survey.step7') }}"><img
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
                            Условия вывода средств
                        </h1>
                        
                        <div class="terms-container">
                            <h2>Парк {{ $parkName ?? 'Таксопарк' }}</h2>
                            
                            <ul class="terms-list">
                                <li>Вывод средств производится каждый день с 10:00 до 18:00</li>
                                <li>Минимальная сумма вывода - 500 сом</li>
                                <li>Комиссия за вывод средств - 0%</li>
                                <li>Срок зачисления средств - мгновенно</li>
                                <li>Вывод возможен на банковские карты, электронные кошельки или наличными в офисе</li>
                                <li>Для вывода средств необходимо предъявить документ, удостоверяющий личность</li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('driver.survey.step7') }}" class="main__btn-active" style="display: block; text-align: center; padding: 15px; text-decoration: none; color: white;">
                            Вернуться назад
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html> 