@extends('disp.layout')
@section('title', 'Новый заказ - taxi.wazir.kg')
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
        <div class="main__header-search-item">
            <button class="main__btn">+ Новый (F2)</button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">Свободные</button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">На заказе</button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">Отмененные</button>
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
<div class="main__order-wrapper">
    <div class="main__order-wrapper-blocks">
        <div class="main__order-settings">
            <div class="main__order-header">
                <div class="main__order-header-item">
                    <p>Заказ №</p>
                    <button class="main__btn-short">299920909</button>
                </div>
                <div class="main__order-header-item">
                    <p>Дата время</p>
                    <button class="main__btn-short">16.07.24</button>
                    <button class="main__btn-short">21:50</button>
                </div>
                <div class="main__order-header-item">
                    <p>Путевой лист</p>
                    <button class="main__btn-short">12345678</button>
                </div>
            </div>
            <div class="main__order-subheader">
                <div class="main__order-subheader-item">
                    <button class="main__btn-short">+996 (555)123456</button>
                    <button class="main__btn-short">Сергей Лавров</button>
                </div>
                <div class="main__order-subheader-item">
                    <div class="main__subheader-filing">
                        <form action="#">
                            <select name="filing-date">
                                <option value="Вариант" disabled selected>Вариант</option>
                                <option value="Эконом">Эконом</option>
                                <option value="Люкс">Люкс</option>
                            </select>
                        </form>
                    </div>
                    <div class="main__subheader-filing">
                        <form action="#">
                            <select name="filing-date">
                                <option value="Оплата" disabled selected>Оплата</option>
                                <option value="Наличные">Наличные</option>
                                <option value="Приложение">Приложение</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="main__order-details">
                <div class="main__order-details-item main__order-details-where">
                    <button class="main__btn"><span>ул.</span>Курманжан-Датка</button>
                    <button class="main__btn"><span>д.</span>215-5Б</button>
                    <button class="main__btn"><span>р-н.</span>Араванский</button>
                </div>
                <div class="main__order-details-item main__order-details-whither">
                    <button class="main__btn"><span>ул.</span>Курманжан-Датка</button>
                    <button class="main__btn"><span>д.</span>215-5Б</button>
                    <button class="main__btn"><span>р-н.</span>Араванский</button>
                </div>
            </div>
            <div class="main__order-notes">
                <div class="main__order-notes-text">
                    <form>
                        <textarea id="subheader__input-item" placeholder="Примечание"></textarea>
                    </form>
                </div>
                <div class="main__order-notes-settings">
                    <button class="main__btn-short">Заказ другому человеку</button>
                    <button class="main__btn-short">Дополнительные услуги</button>
                </div>
            </div>
            <div class="main__table">
                <table>
                    <thead>
                        <tr>
                            <th>Заказ</th>
                            <th>Время</th>
                            <th>Откуда</th>
                            <th>Куда</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>123456789</td>
                            <td>10:10 29.06.24</td>
                            <td>ул. Курчатова 3</td>
                            <td>ул. Курчатова 5</td>
                            <td>12000</td>
                        </tr>
                        <tr>
                            <td>123456789</td>
                            <td>10:10 29.06.24</td>
                            <td>ул. Курчатова 3</td>
                            <td>ул. Курчатова 5</td>
                            <td>12000</td>
                        </tr>
                        <tr>
                            <td>123456789</td>
                            <td>10:10 29.06.24</td>
                            <td>ул. Курчатова 3</td>
                            <td>ул. Курчатова 5</td>
                            <td>12000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="main__order-map">
            <div class="main__order-map-item">
                <p>Тут скоро будет карта, она разрабатывается!</p>
            </div>
            <div class="main__order-map-settings">
                <div class="main__order-map-settings">
                    <button class="main__btn">Данные для рассчета</button>
                </div>
                <div class="main__order-map-settings-item">
                    <button class="main__btn-short">Эконом</button>
                    <button class="main__btn-short">Посадка</button>
                    <button class="main__btn-short">Рассчетный</button>
                    <button class="main__btn-short">Стоимость</button>
                </div>
            </div>
        </div>
    </div>
    <div class="main__order-wrapper-btn">
        <button class="main__btn-green">Заказать</button>
        <button class="main__btn">Отменить</button>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush