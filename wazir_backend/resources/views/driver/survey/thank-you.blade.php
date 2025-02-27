<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Анкета отправлена - Wazir.kg</title>
        <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
        <style>
            .success-content {
                text-align: center;
                padding: 40px 0;
            }
            
            .success-icon {
                font-size: 80px;
                color: #4CAF50;
                margin-bottom: 20px;
            }
            
            .success-title {
                font-size: 28px;
                color: #333;
                margin-bottom: 15px;
            }
            
            .success-description {
                color: #666;
                font-size: 16px;
                max-width: 600px;
                margin: 0 auto 30px;
                line-height: 1.6;
            }
            
            .success-info {
                background: #f9f9f9;
                border-radius: 10px;
                padding: 20px;
                max-width: 600px;
                margin: 0 auto 30px;
                text-align: left;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
            
            .success-info-title {
                font-size: 18px;
                color: #333;
                margin-bottom: 10px;
                text-align: center;
            }
            
            .info-item {
                margin-bottom: 8px;
                display: flex;
            }
            
            .info-label {
                font-weight: 500;
                width: 200px;
                color: #555;
            }
            
            .info-value {
                color: #333;
                flex: 1;
            }
            
            .buttons-container {
                margin-top: 30px;
            }
            
            .main__btn-active {
                display: inline-block;
                text-decoration: none;
                margin: 0 10px;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="brand">
                <div class="container">
                    <div class="brand__content">
                        <div class="logo">
                            <a href="{{ route('driver.survey.step1') }}"><img src="{{ asset('assets/img/driver/logo.png') }}" alt="logo"></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <section class="survey-3">
                <div class="container">
                    <div class="success-content">
                        <div class="success-icon">✓</div>
                        <h1 class="success-title">Ваша анкета успешно отправлена!</h1>
                        <p class="success-description">
                            Благодарим вас за заполнение анкеты. Мы рассмотрим вашу заявку в ближайшее время.
                            Наш специалист свяжется с вами по указанному номеру телефона для уточнения деталей.
                        </p>
                        
                        <div class="success-info">
                            <h2 class="success-info-title">Данные вашей заявки</h2>
                            
                            <div class="info-item">
                                <span class="info-label">ФИО:</span>
                                <span class="info-value">{{ $driver->name }}</span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Телефон:</span>
                                <span class="info-value">{{ $driver->phone }}</span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Номер заявки:</span>
                                <span class="info-value">WZ-{{ $driver->id }}-{{ date('Ymd') }}</span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label">Дата отправки:</span>
                                <span class="info-value">{{ date('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="buttons-container">
                            <a href="/" class="main__btn-active">На главную</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html> 