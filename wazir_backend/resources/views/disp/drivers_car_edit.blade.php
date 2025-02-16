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
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_num_edit') }}">Автомобиль</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
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
                <label for="photo_1">Фото Автомобиля <br> (Автомобиль спереди)</label>
                <div class="form-group-image">
                    <input name="photo_1" style="display: none;">
                    <div class="form-group-image-item">
                        <img src="{{ asset('assets/img/disp/car/1.png') }}" alt="1">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="dob">Фото автомобиля <br> (Автомобиль сзади)</label>
                <img src="{{ asset('assets/img/disp/car/1.png') }}" alt="1">
            </div>
            <div class="form-group">
                <label for="personal-number">Фото с ВУ <br> (Водительское удостоверение)</label>
                <img src="{{ asset('assets/img/disp/car/5.png') }}" alt="5">
            </div>
        </div>

        <div class="form-column">
            <div class="form-group">
                <label for="license-num">Фото автомобиля<br>(Автомобиль справа)</label>
                <img src="{{ asset('assets/img/disp/car/2.png') }}" alt="2">
            </div>
            <div class="form-group">
                <label for="issue-date">Фото автомобиля <br> (Автомобиль слева)</label>
                <img src="{{ asset('assets/img/disp/car/4.png') }}" alt="4">
            </div>
            <div class="form-group main__drivers-car-salon">
                <label for="expiry-date">Фото Салона<br>Спереди и сзади</label>
                <div class="main__drivers-car">
                    <img src="{{ asset('assets/img/disp/car/6.png') }}" alt="6">
                    <img src="{{ asset('assets/img/disp/car/6.png') }}" alt="6">
                </div>
            </div>
        </div>
    </form>
    <div class="main__driverscar-btn">
        <button class="main__btn-green">Готова</button>
        <button class="main__btn">Не совпадает</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush