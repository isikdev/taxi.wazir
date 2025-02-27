<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/upload-fix.js') }}"></script>
    <style>
    .upload-section {
        margin-top: 25px;
    }

    .document-uploads {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .upload-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        position: relative;
    }

    .upload-header {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .upload-description {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .upload-box {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 25px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: #fff;
        width: 100%;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }

    .upload-box:hover {
        border-color: #4CAF50;
        background-color: #f9fff9;
    }

    .upload-box .icon {
        font-size: 30px;
        color: #4CAF50;
        margin-bottom: 10px;
        line-height: 1;
    }

    .upload-box p {
        margin: 0;
        color: #555;
        font-size: 14px;
    }

    .upload-box input[type="file"] {
        display: none;
    }

    .preview-container {
        display: none;
        margin-top: 15px;
        position: relative;
    }

    .preview-container img {
        width: 100%;
        border-radius: 8px;
        max-height: 150px;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 25px;
        height: 25px;
        text-align: center;
        line-height: 25px;
        cursor: pointer;
        color: #f44336;
        font-weight: bold;
    }

    .progress-container {
        margin-top: 10px;
        display: none;
    }

    .progress-bar {
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background-color: #4CAF50;
        width: 0%;
        transition: width 0.3s ease;
    }

    .upload-status {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    .main__btn {
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .document-uploads {
            grid-template-columns: 1fr;
        }

        .upload-box {
            height: 120px;
            padding: 15px;
        }

        .upload-item {
            margin-bottom: 15px;
        }
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
                                src="{{ asset('assets/img/driver/ico/back.svg') }}" alt="back"></a>
                    </div>
                </div>
            </div>
        </header>
        <section class="survey-3">
            <div class="container">
                <div class="survey__content">
                    <h1 class="title-left">
                        Загрузите документы
                    </h1>

                    <form action="{{ route('driver.survey.processStep8') }}" method="POST" enctype="multipart/form-data"
                        id="documentForm">
                        @csrf

                        @if(request()->has('redirect_to_complete'))
                        <input type="hidden" name="redirect_to_complete" value="1">
                        @endif

                        <div class="upload-section">
                            <h2 class="sub-title">Паспорт</h2>

                            <div class="document-uploads">
                                <!-- Паспорт - лицевая сторона -->
                                <div class="upload-item">
                                    <div class="upload-header">Лицевая сторона</div>
                                    <div class="upload-description">Загрузите скан или фото лицевой стороны паспорта
                                    </div>

                                    <input type="file" name="passport_front" id="passportFront" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="passportFront" class="upload-box" id="passportFrontBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="passportFrontPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="passportFront">×</div>
                                    </div>

                                    <div class="progress-container" id="passportFrontProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Паспорт - задняя сторона -->
                                <div class="upload-item">
                                    <div class="upload-header">Задняя сторона</div>
                                    <div class="upload-description">Загрузите скан или фото задней стороны паспорта
                                    </div>

                                    <input type="file" name="passport_back" id="passportBack" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="passportBack" class="upload-box" id="passportBackBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="passportBackPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="passportBack">×</div>
                                    </div>

                                    <div class="progress-container" id="passportBackProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="upload-section">
                            <h2 class="sub-title">Водительское удостоверение</h2>

                            <div class="document-uploads">
                                <!-- ВУ - лицевая сторона -->
                                <div class="upload-item">
                                    <div class="upload-header">Лицевая сторона</div>
                                    <div class="upload-description">Загрузите скан или фото лицевой стороны
                                        водительского удостоверения</div>

                                    <input type="file" name="driving_license_front" id="drivingLicenseFront"
                                        accept="image/*" class="document-upload" style="display:none;">
                                    <label for="drivingLicenseFront" class="upload-box" id="drivingLicenseFrontBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="drivingLicenseFrontPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="drivingLicenseFront">×</div>
                                    </div>

                                    <div class="progress-container" id="drivingLicenseFrontProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ВУ - задняя сторона -->
                                <div class="upload-item">
                                    <div class="upload-header">Задняя сторона</div>
                                    <div class="upload-description">Загрузите скан или фото задней стороны водительского
                                        удостоверения</div>

                                    <input type="file" name="driving_license_back" id="drivingLicenseBack"
                                        accept="image/*" class="document-upload" style="display:none;">
                                    <label for="drivingLicenseBack" class="upload-box" id="drivingLicenseBackBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="drivingLicenseBackPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="drivingLicenseBack">×</div>
                                    </div>

                                    <div class="progress-container" id="drivingLicenseBackProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="upload-section">
                            <h2 class="sub-title">Фотографии автомобиля</h2>

                            <div class="document-uploads">
                                <!-- Автомобиль - вид спереди -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид спереди</div>
                                    <div class="upload-description">Загрузите фото автомобиля спереди</div>

                                    <input type="file" name="car_front" id="carFront" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="carFront" class="upload-box" id="carFrontBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="carFrontPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="carFront">×</div>
                                    </div>

                                    <div class="progress-container" id="carFrontProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Автомобиль - вид сзади -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид сзади</div>
                                    <div class="upload-description">Загрузите фото автомобиля сзади</div>

                                    <input type="file" name="car_back" id="carBack" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="carBack" class="upload-box" id="carBackBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="carBackPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="carBack">×</div>
                                    </div>

                                    <div class="progress-container" id="carBackProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Автомобиль - вид слева -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид слева</div>
                                    <div class="upload-description">Загрузите фото автомобиля слева</div>

                                    <input type="file" name="car_left" id="carLeft" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="carLeft" class="upload-box" id="carLeftBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="carLeftPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="carLeft">×</div>
                                    </div>

                                    <div class="progress-container" id="carLeftProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Автомобиль - вид справа -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид справа</div>
                                    <div class="upload-description">Загрузите фото автомобиля справа</div>

                                    <input type="file" name="car_right" id="carRight" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="carRight" class="upload-box" id="carRightBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="carRightPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="carRight">×</div>
                                    </div>

                                    <div class="progress-container" id="carRightProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="upload-section">
                            <h2 class="sub-title">Фотографии салона</h2>

                            <div class="document-uploads">
                                <!-- Салон - вид спереди -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид спереди</div>
                                    <div class="upload-description">Загрузите фото салона спереди</div>

                                    <input type="file" name="interior_front" id="interiorFront" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="interiorFront" class="upload-box" id="interiorFrontBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="interiorFrontPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="interiorFront">×</div>
                                    </div>

                                    <div class="progress-container" id="interiorFrontProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Салон - вид сзади -->
                                <div class="upload-item">
                                    <div class="upload-header">Вид сзади</div>
                                    <div class="upload-description">Загрузите фото салона сзади</div>

                                    <input type="file" name="interior_back" id="interiorBack" accept="image/*"
                                        class="document-upload" style="display:none;">
                                    <label for="interiorBack" class="upload-box" id="interiorBackBox">
                                        <div class="icon">+</div>
                                        <p>Нажмите для загрузки</p>
                                    </label>

                                    <div class="preview-container" id="interiorBackPreview">
                                        <img src="#" alt="Предпросмотр">
                                        <div class="remove-image" data-target="interiorBack">×</div>
                                    </div>

                                    <div class="progress-container" id="interiorBackProgress">
                                        <div class="progress-bar">
                                            <div class="progress-fill"></div>
                                        </div>
                                        <div class="upload-status">
                                            <span class="status-text">Загрузка...</span>
                                            <span class="status-percent">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn" class="main__btn" disabled>Продолжить</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
    // Проверка, что jQuery загружен
    $(document).ready(function() {
        console.log('Страница загружена и jQuery работает!');

        // Сбрасываем все инпуты при загрузке страницы
        $('.document-upload').val('');

        // Проверяем, правильно ли подключен наш скрипт
        if (typeof checkSubmitButton === 'function') {
            console.log('Функция checkSubmitButton успешно загружена');
        } else {
            console.error('Функция checkSubmitButton не найдена! Проверьте подключение скрипта upload-fix.js');

            // Определяем функцию на случай, если скрипт не загрузился
            window.checkSubmitButton = function() {
                let allUploaded = true;
                $('.document-upload').each(function() {
                    if (!this.files || !this.files[0]) {
                        allUploaded = false;
                        return false; // прерываем цикл
                    }
                });

                if (allUploaded) {
                    $('#submitBtn').prop('disabled', false).removeClass('main__btn').addClass(
                        'main__btn-active');
                } else {
                    $('#submitBtn').prop('disabled', true).removeClass('main__btn-active').addClass(
                        'main__btn');
                }
            }
        }

        // Переопределяем обработчик выбора файла для изменения класса кнопки
        $('.document-upload').on('change', function() {
            const inputId = $(this).attr('id');
            const file = this.files[0];

            if (file) {
                // Показываем предпросмотр
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#${inputId}Preview`).show().find('img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                // Эмуляция загрузки с прогресс-баром
                const progressContainer = $(`#${inputId}Progress`);
                progressContainer.show();
                const progressFill = progressContainer.find('.progress-fill');
                const percentText = progressContainer.find('.status-percent');

                let percent = 0;
                const interval = setInterval(function() {
                    percent += 5;
                    progressFill.css('width', percent + '%');
                    percentText.text(percent + '%');

                    if (percent >= 100) {
                        clearInterval(interval);
                        progressContainer.find('.status-text').text('Загружено');

                        // Проверяем, все ли файлы загружены для активации кнопки
                        let allComplete = true;
                        $('.progress-container').each(function() {
                            // Если какой-то прогресс-бар не показан или не на 100%, значит не все загружено
                            if (!$(this).is(':visible') || $(this).find(
                                    '.progress-fill').width() < $(this).find(
                                    '.progress-bar').width()) {
                                allComplete = false;
                                return false;
                            }
                        });

                        // Если все файлы загружены, активируем кнопку и меняем ее класс
                        if (allComplete) {
                            $('#submitBtn').prop('disabled', false).removeClass('main__btn')
                                .addClass('main__btn-active');
                        }
                    }
                }, 100);
            }
        });

        // Обработка удаления изображения
        $('.remove-image').on('click', function() {
            const targetId = $(this).data('target');
            $(`#${targetId}`).val('');
            $(`#${targetId}Preview`).hide();
            $(`#${targetId}Progress`).hide().find('.progress-fill').css('width', '0%');
            $(`#${targetId}Progress .status-text`).text('Загрузка...');
            $(`#${targetId}Progress .status-percent`).text('0%');

            // После удаления изображения возвращаем кнопке исходное состояние
            $('#submitBtn').prop('disabled', true).removeClass('main__btn-active').addClass(
                'main__btn');
        });
    });
    </script>
</body>

</html>