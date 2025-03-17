<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение анкеты Wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/driver/main.css') }}">
    <style>
    .edit-section {
        margin-bottom: 30px;
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .edit-header h2 {
        font-size: 20px;
        color: #333;
        margin: 0;
    }

    .edit-button {
        background: transparent;
        border: none;
        color: #4A90E2;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .edit-button svg {
        margin-right: 5px;
        width: 16px;
        height: 16px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .form-group {
        flex: 0 0 100%;
        padding: 0 10px;
        margin-bottom: 15px;
    }

    @media (min-width: 768px) {
        .form-group.half {
            flex: 0 0 50%;
        }
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        color: #666;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 10px 15px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        color: #333;
    }

    .form-control:disabled {
        background-color: #f9f9f9;
        color: #777;
        cursor: not-allowed;
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    @media (min-width: 768px) {
        .documents-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .document-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background: #f5f5f5;
        padding-bottom: 75%;
        /* Соотношение сторон 4:3 */
    }

    .document-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .document-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 5px 10px;
        font-size: 12px;
        text-align: center;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: #666;
        margin-bottom: 20px;
        text-decoration: none;
    }

    .back-link svg {
        margin-right: 5px;
    }

    .submit-wrapper {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <header>
        <div class="back">
            <div class="container">
                <div class="back__content">
                    <a href="{{ route('driver.survey.step8') }}"><img
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
                        Проверка и отправка анкеты
                    </h1>

                    <p>Проверьте правильность заполненных данных. При необходимости вы можете вернуться и исправить
                        информацию.</p>

                    <!-- Личная информация -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Личная информация</h2>
                            <a href="{{ route('driver.survey.step3', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ФИО</label>
                                <input type="text" class="form-control" value="{{ $data['fullname'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Телефон <span style="color: #999; font-size: 12px;">(нельзя
                                        изменить)</span></label>
                                <input type="text" class="form-control" value="{{ $data['phone'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Город</label>
                                <input type="text" class="form-control" value="{{ $data['city'] ?? '' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Водительское удостоверение -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Водительское удостоверение</h2>
                            <a href="{{ route('driver.survey.step3', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label class="form-label">Номер удостоверения</label>
                                <input type="text" class="form-control" value="{{ $data['license_number'] ?? '' }}"
                                    disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Страна выдачи</label>
                                <input type="text" class="form-control" value="{{ $data['country'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Дата выдачи</label>
                                <input type="text" class="form-control" value="{{ $data['issue_date'] ?? '' }}"
                                    disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Срок действия</label>
                                <input type="text" class="form-control"
                                    value="{{ $data['expiry_date'] ?? 'Не указан' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Автомобиль -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Информация об автомобиле</h2>
                            <a href="{{ route('driver.survey.step4', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label class="form-label">Марка автомобиля</label>
                                <input type="text" class="form-control" value="{{ $data['car_brand'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Модель автомобиля</label>
                                <input type="text" class="form-control" value="{{ $data['car_model'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Цвет автомобиля</label>
                                <input type="text" class="form-control" value="{{ $data['car_color'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Год выпуска</label>
                                <input type="text" class="form-control" value="{{ $data['car_year'] ?? '' }}" disabled>
                            </div>

                            <div class="form-group half">
                                <label class="form-label">Государственный номер</label>
                                <input type="text" class="form-control" value="{{ $data['license_plate'] ?? '' }}"
                                    disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">VIN</label>
                                <input type="text" class="form-control" value="{{ $data['vin'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Номер кузова</label>
                                <input type="text" class="form-control" value="{{ $data['body_number'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">СТС</label>
                                <input type="text" class="form-control" value="{{ $data['sts'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">КПП</label>
                                <input type="text" class="form-control" value="{{ $data['transmission'] ?? '' }}" disabled>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Дополнительная информация -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Дополнительная информация</h2>
                            <a href="{{ route('driver.survey.step4', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label class="form-label">Бустеры</label>
                                <input type="text" class="form-control" value="{{ $data['boosters'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Тариф</label>
                                <input type="text" class="form-control" value="{{ $data['tariff'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Услуга</label>
                                <input type="text" class="form-control" value="{{ $data['service_type'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Категория</label>
                                <input type="text" class="form-control" value="{{ $data['category'] ?? '' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Детское кресло</label>
                                <input type="text" class="form-control" value="{{ $data['child_seat'] ?? 'Нет' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Наклейка</label>
                                <input type="text" class="form-control" value="{{ $data['has_nakleyka'] == '1' ? 'Да' : 'Нет' }}" disabled>
                            </div>
                            
                            <div class="form-group half">
                                <label class="form-label">Лайтбокс - Шашка</label>
                                <input type="text" class="form-control" value="{{ $data['has_lightbox'] == '1' ? 'Да' : 'Нет' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Таксопарк -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Выбранный таксопарк</h2>
                            <a href="{{ route('driver.survey.step6', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Название таксопарка</label>
                                <input type="text" class="form-control" value="{{ $data['park_name'] ?? 'Не выбран' }}"
                                    disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Загруженные документы -->
                    <div class="edit-section">
                        <div class="edit-header">
                            <h2>Загруженные документы</h2>
                            <a href="{{ route('driver.survey.step8', ['redirect_to_complete' => 1]) }}"
                                class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                                Изменить
                            </a>
                        </div>

                        <h3>Паспорт</h3>
                        <div class="documents-grid">
                            @if(isset($data['documents']['passport_front']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['passport_front']) }}"
                                    alt="Паспорт (лицевая сторона)">
                                <p>Паспорт (лицевая сторона)</p>
                            </div>
                            @endif

                            @if(isset($data['documents']['passport_back']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['passport_back']) }}"
                                    alt="Паспорт (обратная сторона)">
                                <p>Паспорт (обратная сторона)</p>
                            </div>
                            @endif
                        </div>

                        <h3>Водительское удостоверение</h3>
                        <div class="documents-grid">
                            @if(isset($data['documents']['driving_license_front']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['license_front']) }}"
                                    alt="Водительское удостоверение (лицевая сторона)">
                                <p>Водительское удостоверение (лицевая сторона)</p>
                            </div>
                            @endif

                            @if(isset($data['documents']['driving_license_back']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['license_back']) }}"
                                    alt="Водительское удостоверение (обратная сторона)">
                                <p>Водительское удостоверение (обратная сторона)</p>
                            </div>
                            @endif
                        </div>

                        <h3>Фотографии автомобиля</h3>
                        <div class="documents-grid">
                            @if(isset($data['documents']['car_front']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['car_front']) }}" alt="Вид спереди">
                                <div class="document-label">Вид спереди</div>
                            </div>
                            @endif

                            @if(isset($data['documents']['car_back']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['car_back']) }}" alt="Вид сзади">
                                <div class="document-label">Вид сзади</div>
                            </div>
                            @endif

                            @if(isset($data['documents']['car_left']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['car_left']) }}" alt="Вид слева">
                                <div class="document-label">Вид слева</div>
                            </div>
                            @endif

                            @if(isset($data['documents']['car_right']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['car_right']) }}" alt="Вид справа">
                                <div class="document-label">Вид справа</div>
                            </div>
                            @endif
                        </div>

                        <h3>Фотографии салона</h3>
                        <div class="documents-grid">
                            @if(isset($data['documents']['interior_front']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['interior_front']) }}"
                                    alt="Салон спереди">
                                <div class="document-label">Салон спереди</div>
                            </div>
                            @endif

                            @if(isset($data['documents']['interior_back']))
                            <div class="document-item">
                                <img src="{{ asset('storage/' . $data['documents']['interior_back']) }}"
                                    alt="Салон сзади">
                                <div class="document-label">Салон сзади</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="submit-wrapper">
                        <form action="{{ route('driver.survey.submitApplication') }}" method="POST">
                            @csrf
                            <button type="submit" class="main__btn-active">Отправить анкету</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // При нажатии на изображение - открываем его в большом виде
        $('.document-item img').on('click', function() {
            const imgSrc = $(this).attr('src');

            // Создаем элемент модального окна
            const modal = `
                        <div class="image-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                            <div class="modal-content" style="position: relative; max-width: 90%; max-height: 90%;">
                                <span class="close-modal" style="position: absolute; top: -40px; right: 0; color: white; font-size: 30px; cursor: pointer;">&times;</span>
                                <img src="${imgSrc}" style="max-width: 100%; max-height: 90vh; display: block;">
                            </div>
                        </div>
                    `;

            $('body').append(modal);

            // Закрытие модального окна
            $('.close-modal, .image-modal').on('click', function() {
                $('.image-modal').remove();
            });

            // Предотвращаем закрытие при клике на само изображение
            $('.modal-content img').on('click', function(e) {
                e.stopPropagation();
            });
        });
    });
    </script>
</body>

</html>