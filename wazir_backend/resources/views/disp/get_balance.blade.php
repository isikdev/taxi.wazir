@extends('disp.layout')
@section('title', 'Запросить баланс - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.main__subheader-getbalance {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}
</style>
@endpush
@section('content')

<div class="main__subheader-getbalance">
    <div class="main__subheader-add main__subheader-getbalance-btn">
        <button>Аккаунт</button>
        <button>Архив</button>
    </div>
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
    </div>
</div>
<div class="main__subheader-getbalance-title">
    <h3>Запрос баланса</h3>
</div>
<div class="main__getbalance">
    <div class="main__getbalance-form">
        <div class="main__getbalance-form-intro">
            <h3>Доступный остаток на 17.08.2024 г.</h3>
            <h1>Сумма: 10.000</h1>
            <button class="main__btn">Детали</button>
        </div>
        <div class="main__getbalance-outro">
            <h3>Автоматическое перечисление запланирована на -</h3>
            <div class="main__getbalance-outro-item">
                <p>ID Диспетчера</p>
                <button class="main__btn-short">222222222222</button>
            </div>
            <div class="main__getbalance-outro-item">
                <p>Лицевой счет</p>
                <button class="main__btn-short">222222222222</button>
            </div>
            <button class="main__btn">Детали</button>
        </div>
        <div class="main__getbalance-balance">
            <h3>Запрос баланса</h3>
            <form action="#">
                <p>Сумма</p>
                <input type="text" class="main__input">
            </form>
            <button class="main__btn-green" type="submit">Запросить</button>
        </div>
    </div>
    <div class="main__getbalance-driverinfo">
        <div class="main__table main__getbalance-table">
            <table>
                <thead>
                    <tr>
                        <th style="width: 200px;">Ф.И.О</th>
                        <th style="width: 220px;">Телефон</th>
                        <th style="width: 250px;">Email</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Дональд Трамп</td>
                        <td>+996 (555) 555-555</td>
                        <td>kg5555555555555555@gmail.com</td>
                        <td class="status-free">Активен</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="main__driverinfo-passport">
            <p>ID Паспорт<br>(Персональный Паспорт)</p>
            <div class="main__driverinfo-passport-img">
                <img src="{{ asset('assets/img/disp/passport/1.png') }}" alt="1">
                <img src="{{ asset('assets/img/disp/passport/2.png') }}" alt="2">
            </div>
        </div>
        <div class="main__table main__getbalance-table">
            <table>
                <thead>
                    <tr>
                        <th style="width: 200px;">Статус</th>
                        <th style="width: 220px;">Номер пополнения</th>
                        <th style="width: 250px;">Дата создания</th>
                        <th>Рассчетный счет</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Активирован</td>
                        <td>12345678</td>
                        <td>1 январь 2024 года</td>
                        <td>0123456789</td>
                    </tr>
                    <tr>
                        <td>Активирован</td>
                        <td>12345678</td>
                        <td>1 январь 2024 года</td>
                        <td>0123456789</td>
                    </tr>
                    <tr>
                        <td>Активирован</td>
                        <td>12345678</td>
                        <td>1 январь 2024 года</td>
                        <td>0123456789</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
@endpush