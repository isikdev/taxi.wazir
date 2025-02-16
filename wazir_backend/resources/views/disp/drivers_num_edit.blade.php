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
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Водитель</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
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
            <img src="{{ asset('assets/img/ico/balance.png') }}" alt="balance">
            <p>Баланс: 10,000</p>
        </div>
    </div>
</div>

<div class="main__subheader-getbalance-title">
    <h3>Запросы</h3>
</div>

<div class="main__driversedit-wrapper">
    <h3 class="title">Автомобиль</h3>
    <form class="personal-data-form">
        <div class="form-column">
            <div class="form-group">
                <label for="fio">Марка</label>
                <input type="text" id="fio" name="fio" placeholder>
            </div>
            <div class="form-group">
                <label for="dob">Модель</label>
                <input type="text" id="dob" name="dob" placeholder>
            </div>
            <div class="form-group">
                <label for="personal-number">Цвет</label>
                <input type="text" id="personal-number" name="personal-number" placeholder>
            </div>
            <div class="form-group">
                <label for="personal-number">Год</label>
                <input type="text" id="personal-number" name="personal-number" placeholder>
            </div>
            <h3 class="title">Комплектация и брендинг</h3>
            <div class="form-group">
                <label for="fio">КПП</label>
                <input type="text" id="fio" name="fio" placeholder>
            </div>
            <div class="form-group">
                <label for="dob">Бустеры</label>
                <input type="text" id="dob" name="dob" placeholder>
            </div>
            <div class="form-group">
                <label for="personal-number">Детское кресло</label>
                <input type="text" id="personal-number" name="personal-number" placeholder>
            </div>
            <h3 class="title">Параметры</h3>
            <div class="form-group">
                <label for="fio">Парковая машина</label>
                <input type="text" id="fio" name="fio" placeholder>
            </div>
            <div class="form-group">
                <label for="dob">Позывной</label>
                <input type="text" id="dob" name="dob" placeholder>
            </div>
            <h3 class="title">Тариф</h3>
            <div class="form-group">
                <label for="fio">Тариф</label>
                <input type="text" id="fio" name="fio" placeholder>
            </div>
        </div>

        <div class="form-column">
            <div class="form-group">
                <label for="license-num">Гос.номер</label>
                <input type="text" id="license-num" name="license-num" placeholder>
            </div>
            <div class="form-group">
                <label for="issue-date">VIN</label>
                <input type="text" id="issue-date" name="issue-date" placeholder>
            </div>
            <div class="form-group">
                <label for="expiry-date">Номер кузова</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder>
            </div>
            <div class="form-group">
                <label for="expiry-date">СТС</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder>
            </div>
            <div class="form-group">
                <label for="nakleyka">Наклейка</label>
                <div class="custom-checkbox">
                    <input type="checkbox" class="custom-input">
                    <span class="checkmark"></span>
                </div>
            </div>
            <div class="form-group">
                <label for="nakleyka">Лайтбокс - Шашка</label>
                <div class="custom-checkbox">
                    <input type="checkbox" class="custom-input">
                    <span class="checkmark"></span>
                </div>
            </div>
            <h3 class="title">Услуги</h3>
            <div class="form-group">
                <label for="expiry-date">Услуга</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder>
            </div>
            <div class="form-group">
                <label for="expiry-date">Категория</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder>
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