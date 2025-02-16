@extends('disp.app')
@section('title', 'Главная Диспетчерская - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/scss/main.css') }}">
@endpush
@section('content')
<div class="main__header">
    <div class="main__header-logo">
        <h3>Ош Титан Парк</h3>
    </div>
    <div class="main__header-tags">
        <ul>
            <li><a href="{{ route('dispatcher.backend.drivers') }}">Водитель</a></li>
            <li><a href="{{ route('dispatcher.backend.cars') }}">Автомобиль</a></li>
            <li><a href="{{ route('dispatcher.backend.drivers_control') }}">Фото контроль</a></li>
            <li>Тариф</li>
            <li><a href="{{ route('dispatcher.backend.chat') }}">Чат</a></li>
        </ul>
    </div>
    <div class="main__header-search">
        <div class="main__header-search-item">
            <form action="#">
                <input type="search" placeholder="Поиск">
                <button>
                    <img src="{{ asset('assets/img/ico/search.png') }}" alt="search">
                </button>
            </form>
        </div>
        <div class="main__header-search-profile">
            <div class="main__header-search-profile-item">
                <a href="#">
                    <img src="{{ asset('assets/img/ico/notif.png') }}" alt="notif">
                </a>
            </div>
            <div class="main__header-search-profile-item">
                <a href="#">
                    <img src="{{ asset('assets/img/ico/user.png') }}" alt="profile">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="main__subheader">
    <div class="main__subheader-filter">
        <a href="#"><img src="{{ asset('assets/img/ico/burger.png') }}" alt="burger"></a>
        <a href="#"><img src="{{ asset('assets/img/ico/chart.png') }}" alt="chart"></a>
        <a href="#"><img src="{{ asset('assets/img/ico/earth.png') }}" alt="earth"></a>
    </div>
    <div class="main__subheader-filing">
        <form action="#">
            <select name="filing-date">
                <option value="Дата подачи" disabled selected>Дата подачи</option>
                <option value="08.12.2024">08.12.2024</option>
                <option value="09.01.2025">09.01.2025</option>
            </select>
        </form>
    </div>
    <div class="main__subheader-date">
        <form action="#">
            <input type="date" name="first-date-form" id="first-date-form" disabled>
            <p>-</p>
            <input type="date" name="last-date-form" id="last-date-form" disabled>
        </form>
    </div>
    <div class="main__subheader-add">
        <button><a href="{{ route('dispatcher.backend.new_order') }}">+ Новый (F2)</a></button>
        <button>
            <span class="free"></span>
            Свободные
        </button>
        <button>
            <span class="busy"></span>
            Занятые
        </button>
        <button>
            <span class="cancelled"></span>
            Отмененные
        </button>
    </div>
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
    </div>
</div>
<div class="main__table">
    <table>
        <thead>
            <tr>
                <th>Заказ</th>
                <th>Дата</th>
                <th>Время</th>
                <th>Статус</th>
                <th>Телефон</th>
                <th>Откуда</th>
                <th>Куда</th>
                <th>Позывной</th>
                <th>Стоимость</th>
                <th>Тариф</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-free">Свободен</td>
                <td>+996555001122</td>
                <td>ул. Курчатова 3</td>
                <td></td>
                <td>Позывной</td>
                <td></td>
                <td>Эконом</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-busy">Занят</td>
                <td>+996555001122</td>
                <td>ул. Салиева 12</td>
                <td>ул. Салиева 12</td>
                <td>Позывной</td>
                <td>150</td>
                <td>Эконом</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-busy">Занят</td>
                <td>+996555001122</td>
                <td>ул. Курчатова 12</td>
                <td>ул. Курчатова 12</td>
                <td>Позывной</td>
                <td>150</td>
                <td>Эконом</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-free">Свободен</td>
                <td>+996555001122</td>
                <td>ул. Куранжан-Датка 245</td>
                <td></td>
                <td>Позывной</td>
                <td>150</td>
                <td>Эконом</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-free">Свободен</td>
                <td>+996555001122</td>
                <td>ул. Курчатова 4</td>
                <td></td>
                <td>Позывной</td>
                <td></td>
                <td>Эконом</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-cancelled">Отменен</td>
                <td>+996555001122</td>
                <td>ул. Анар 12/4</td>
                <td>ул. Анар 12/4</td>
                <td>Позывной</td>
                <td>150</td>
                <td>Комфорт</td>
            </tr>
            <tr>
                <td>123456789</td>
                <td>29.06.24</td>
                <td>10:10</td>
                <td class="status-busy">Занят</td>
                <td>+996555001122</td>
                <td>ул. Ак-илек 12</td>
                <td>ул. Ак-илек 12</td>
                <td>Позывной</td>
                <td>150</td>
                <td>Комфорт</td>
            </tr>
        </tbody>
    </table>
    <div class="main__table-footer">
        <div class="main__table-driver">
            <button>Водители: 100</button>
        </div>
        <div class="main__table-pagination">
            <div class="main__table-pagination-prev">
                <button>
                    <img src="{{ asset('assets/img/ico/prev.png') }}" alt="prev">
                </button>
            </div>
            <div class="main__table-pagination-active main__table-pagination-item">
                <button>1</button>
            </div>
            <div class="main__table-pagination-item">
                <button>2</button>
            </div>
            <div class="main__table-pagination-item">
                <button>3</button>
            </div>
            <div class="main__table-pagination-next">
                <button>
                    <img src="{{ asset('assets/img/ico/next.png') }}" alt="next">
                </button>
            </div>
        </div>
    </div>
    <div class="main__map">
        <iframe
            src="https://yandex.ru/map-widget/v1/?um=constructor%3A2b7df6c3be7c21f4b9b40358032ecb2eac507471af87e1e198ff8b2a3b79d300&amp;source=constructor"
            width="100%" height="400" frameborder="0"></iframe>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush