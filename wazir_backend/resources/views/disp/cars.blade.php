@extends('disp.layout')
@section('title', 'Автомобили - taxi.wazir.kg')
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

.main__subheader-drivers-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: unset;
    gap: 20px;
}

.default__cars {
    font-size: 20px;
    text-align: center;
    color: #fff;
}
</style>
@endpush
@section('content')
<div class="main__cars-subheader main__cars-list" style="margin: 20px 0;">
    <div class="main__subheader-filing main__subheader-cars-filter">
        <form action="#">
            <select name="filing-date">
                <option value="Статусы" disabled selected>Статусы</option>
                <option value="Свободен">Свободен</option>
                <option value="Занят">Занят</option>
            </select>
        </form>
        <form action="#">
            <select name="filing-date">
                <option value="Марка" disabled selected>Марка</option>
                <option value="KIA">KIA</option>
                <option value="Honda">Honda</option>
            </select>
        </form>
        <form action="#">
            <select name="filing-date">
                <option value="Модель" disabled selected>Модель</option>
                <option value="Модель 1">Модель 1</option>
                <option value="Модель 2">Модель 2</option>
            </select>
        </form>
        <form action="#">
            <select name="filing-date">
                <option value="Цвет" disabled selected>Цвет</option>
                <option value="Белый">Белый</option>
                <option value="Черный">Черный</option>
            </select>
        </form>
        <form action="#">
            <select name="filing-date">
                <option value="Год выпуска" disabled selected>Год выпуска</option>
                <option value="2007">2007</option>
                <option value="2007">2007</option>
            </select>
        </form>
    </div>
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
    </div>
</div>
<div class="main__table">
    <table>
        <thead>
            <tr>
                <th>Статусы</th>
                <th>Марка</th>
                <th>Модель</th>
                <th>Цвет</th>
                <th>Год</th>
                <th>Гос.номер</th>
                <th>VIN</th>
                <th>Номер кузова</th>
                <th>СТС</th>
                <th>Проверен</th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
    </table>
    <p class="default__cars">Пока нету зарегистрированных в системе автомобилей</p>
    <h4 class="default__cars">Зарегистрированных водителей в системе: {{ $drivers->count() }}</h4>
    <div class="main__table-footer">
        <div class="main__table-driver">
            <button>Водители: {{ $drivers->count() }}</button>
        </div>
        <div class="main__table-pagination">
            <div class="main__table-pagination-prev">
                <button>
                    <img src="{{ asset('assets/img/disp/ico/prev.png') }}" alt="prev">
                </button>
            </div>
            <div class="main__table-pagination-active main__table-pagination-item">
                <button>1</button>
            </div>
            <div class="main__table-pagination-next">
                <button>
                    <img src="{{ asset('assets/img/disp/ico/next.png') }}" alt="next">
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push(' scripts') <script src="https://code.jquery.com/jquery-3.7.1.min.js">
</script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
@endpush