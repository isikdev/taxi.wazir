<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
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

    .photo-upload {
        margin-top: 20px;
    }

    .upload-box {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        margin-bottom: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-box:hover {
        border-color: #4CAF50;
    }

    .upload-box .icon {
        font-size: 48px;
        color: #4CAF50;
        margin-bottom: 10px;
    }

    .upload-box p {
        margin: 0;
        color: #555;
    }

    .upload-box input[type="file"] {
        display: none;
    }

    .preview-container {
        display: none;
        margin-top: 15px;
    }

    .preview-container img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
    }

    .survey__contacts {
        margin-top: 30px;
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }

    .survey__contacts h2 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
    }

    .contact-item {
        display: flex;
        margin-bottom: 15px;
        flex-direction: column;
    }

    .contact-label {
        font-weight: 500;
        color: #666;
        margin-bottom: 5px;
    }

    .contact-value {
        color: #333;
    }

    .main__btn-active a {
        color: white;
        text-decoration: none;
        display: block;
        width: 100%;
        height: 100%;
    }
    </style>
</head>

<body>
    <header>
        <div class="back">
            <div class="container">
                <div class="back__content">
                    <a href="{{ route('driver.survey.step6') }}"><img
                            src="{{ asset('assets/img/driver/ico/back.svg') }}" alt="back"></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="survey-3">
            <div class="container">
                <div class="survey__content">
                    <h1 class="title-left">
                        {{ $parkName ?? 'Таксопарк' }}
                    </h1>

                    <div class="survey__park">
                        <div class="survey__park-item">
                            <a href="{{ route('driver.survey.step7', ['page' => 'terms']) }}">Условия вывода средств</a>
                            <img src="{{ asset('assets/img/driver/ico/next.svg') }}" alt="next">
                        </div>
                    </div>

                    <div class="survey__contacts">
                        <h2>Контакты</h2>

                        <div class="contact-item">
                            <span class="contact-label">Ваш телефон</span>
                            <span class="contact-value">
                                @php
                                // Гарантируем, что телефон будет отображаться с кодом +996
                                $displayPhone = $phone ?? '+996 XXX XXX XXX';
                                // Если телефон не начинается с +996, добавляем код
                                if (!empty($phone) && !str_starts_with($displayPhone, '+996')) {
                                $displayPhone = '+996' . preg_replace('/[^0-9]/', '', $displayPhone);
                                }
                                // Форматируем для улучшения читаемости
                                if (!empty($phone) && strlen($displayPhone) >= 13) {
                                $phoneDigits = preg_replace('/[^0-9]/', '', $displayPhone);
                                if (strlen($phoneDigits) >= 12) { // Например: 996XXXXXXXXX
                                $displayPhone = '+' . substr($phoneDigits, 0, 3) . ' ' .
                                substr($phoneDigits, 3, 3) . ' ' .
                                substr($phoneDigits, 6, 2) . ' ' .
                                substr($phoneDigits, 8, 2) . ' ' .
                                substr($phoneDigits, 10);
                                }
                                }
                                @endphp
                                {{ $displayPhone }}
                            </span>
                        </div>

                        <div class="contact-item">
                            <span class="contact-label">Телефон парка</span>
                            <span class="contact-value">{{ $parkPhone ?? '+996 550 123 456' }}</span>
                        </div>

                        <div class="contact-item">
                            <span class="contact-label">График работы</span>
                            <span class="contact-value">
                                {{ $workHours ?? 'Пн-Сб 10:00-18:00' }}<br>
                                {{ $weekend ?? 'Вс-выходной' }}
                            </span>
                        </div>

                        <div class="contact-item">
                            <span class="contact-label">Почта</span>
                            <span class="contact-value">{{ $email ?? 'Example@gmail.com' }}</span>
                        </div>

                        <div class="contact-item">
                            <span class="contact-label">Адрес</span>
                            <span
                                class="contact-value">{{ $address ?? 'Кыргыстан г. Ок мкр Анар 1, (орентир Автомойка Нурзаман, кафе Нирвана)' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('driver.survey.processStep7') }}" method="POST">
                        @csrf
                        <input type="hidden" name="confirm_park" value="1">
                        <button type="submit" class="main__btn-active">
                            Выбрать этот парк
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
    const photoUpload = document.getElementById('photo-upload');
    const photoPreview = document.getElementById('photo-preview');
    const previewContainer = document.getElementById('preview-container');

    photoUpload.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                previewContainer.style.display = 'block';
            }

            reader.readAsDataURL(file);
        }
    });
    </script>
</body>

</html>