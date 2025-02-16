@extends('disp.layout')

@section('title', 'Водители - taxi.wazir.kg')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/disp/css/main.css') }}">
<style>
.main__subheader-drivers {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: center;
}

.main__subheader {
    display: none;
}

.default__cars {
    font-size: 20px;
    text-align: center;
    display: flex;
    justify-content: center;
    margin: 0 auto;
    color: #fff;
}
</style>
@endpush

@section('content')

<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__header-search-item">
            <form action="#" style="background-color: #47484c;">
                <input type="search" placeholder="Поиск">
                <button style="padding: 0px;">
                    <img src="{{ asset('assets/img/disp/ico/search.png') }}" alt="search">
                </button>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="filing-date">
                    <option value="Дата подачи" disabled selected>Статусы</option>
                    <option value="Занят">Занят</option>
                    <option value="В ожидании">В ожидании</option>
                </select>
            </form>
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

<div class="main__cards-wrapper">
    <!-- <div class="main__card-item">
        <img src="{{ asset('assets/img/disp/passport/1.png') }}" alt="1">
        <button class="main__btn">Сергей Викторович Лавров</button>
        <button class="main__btn">Дата регистрации: 01.01.2024</button>
        <button class="main__btn">Посмотреть анкету</button>
        <div class="main__card-btn">
            <button class="main__btn-green">Одобрить</button>
            <button class="main__btn-short">Отменить</button>
        </div>
    </div> -->
    <p class="default__cars">
        Пока нету активных заявок
    </p>
</div>
@endsection

@push('scripts')
<script src=" https://code.jquery.com/jquery-3.7.1.min.js">
</script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
@endpush