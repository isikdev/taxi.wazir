@extends('disp.layout')
@section('title', 'Создание водителя - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__subheader-drivers {
    width: unset;
}
</style>
@endpush
@section('content')

<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
                <a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Водитель</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_num_edit') }}">Автомобиль</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_car_edit') }}">Фото автомобиля</a>
            </button>
        </div>
    </div>
    <div class="main__subheader-drivers">
        <div class="main__header-tags main__subheader-drivers-tags">
            <ul>
                <li>На линии 26 водителей</li>
                <li><span class="status-span free"></span> 12 свободный</li>
                <li><span class="status-span busy"></span> 14 занят</li>
            </ul>
        </div>
        <div class="main__subheader-balance">
            <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
            <p>Баланс: 10,000</p>
        </div>
    </div>
</div>

<div class="main__subheader-getbalance-title">
    <h3>Запросы</h3>
</div>

<div class="main__driversedit-wrapper">
    <h3 class="title">Персональные данные</h3>
    <form class="personal-data-form">
        <div class="form-column">
            <div class="form-group">
                <label for="fio">Ф.И.О.</label>
                <input type="text" id="fio" name="fio" placeholder="Лавров Сергей Викторович" />
            </div>
            <div class="form-group">
                <label for="dob">Дата рождения</label>
                <input type="text" id="dob" name="dob" placeholder="26.06.2024" />
            </div>
            <div class="form-group">
                <label for="personal-number">Персональный номер</label>
                <input type="text" id="personal-number" name="personal-number" placeholder="2222819937793" />
            </div>

            <div class="form-group upload-group">
                <div class="upload-label">
                    Загрузите ID<br>(Персональный Паспорт)<br>Лицевая сторона
                </div>
                <label class="upload-box">
                    <input type="file" name="passport-front" />
                    <span class="upload-icon">+</span>
                </label>
            </div>

            <div class="form-group upload-group">
                <div class="upload-label">
                    Загрузите ВУ<br>(Водительский удостоверения)<br>Лицевая сторона
                </div>
                <label class="upload-box">
                    <input type="file" name="drivers-front" />
                    <span class="upload-icon">+</span>
                </label>
            </div>
        </div>

        <div class="form-column">
            <div class="form-group">
                <label for="license-num">ВУ (№ Удостоверения)</label>
                <input type="text" id="license-num" name="license-num" placeholder="012345678" />
            </div>
            <div class="form-group">
                <label for="issue-date">Дата выдачи</label>
                <input type="text" id="issue-date" name="issue-date" placeholder="23.06.2024" />
            </div>
            <div class="form-group">
                <label for="expiry-date">Срок действия</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder="23.06.2024" />
            </div>

            <div class="form-group upload-group">
                <div class="upload-label">
                    Загрузите ID<br>(Персональный Паспорт)<br>Задняя сторона
                </div>
                <label class="upload-box">
                    <input type="file" name="passport-back" />
                    <span class="upload-icon">+</span>
                </label>
            </div>

            <div class="form-group upload-group">
                <div class="upload-label">
                    Загрузите ВУ<br>(Водительский удостоверения)<br>Задняя сторона
                </div>
                <label class="upload-box">
                    <input type="file" name="drivers-back" />
                    <span class="upload-icon">+</span>
                </label>
            </div>
        </div>
    </form>
    <div class="action-buttons">
        <button type="submit" class="main__btn-green">Сохранить</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush