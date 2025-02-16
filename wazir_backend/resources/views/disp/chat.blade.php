@extends('disp.layout')
@section('title', 'Чат - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
<style>
.main__table-wrapper {
    display: flex;
    gap: 10px;
    justify-content: space-between;
}

.main__chat-table-wrapper {
    width: 100%;
}

.main__table {
    width: 100%;
    margin: 0;
}

.main__table-chat-group:first-child {
    margin: 0;
}

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
</style>
@endpush
@section('content')
@php
$users = $users ?? collect();
@endphp
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
                    <option value="Статусы" disabled selected>Статусы</option>
                    <option value="Занят">Занят</option>
                    <option value="В ожидании">В ожидании</option>
                </select>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="filing-date">
                    <option value="Состояние" disabled selected>Состояние</option>
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
<div class="main__chat-wrapper">
    <div class="main__table-wrapper main__chat-table-wrapper">
        <div class="main__table main__getbalance-table">
            <table>
                <thead>
                    <tr>
                        <th>Статусы</th>
                        <th>Состояние</th>
                        <th>Позывной</th>
                        <th>Ф.И.О</th>
                        <th>Телефон</th>
                        <th>СМС</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><span class="status-busy">Новый пользователь</span></td>
                        <td><span class="status-busy">Новый пользователь</span></td>
                        <td>{{ $user->call_sign }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            <a href="#">
                                <img src="{{ asset('assets/img/disp/ico/sms.png') }}" alt="sms">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="main__table-chat-group-wrapper">
            <div class="main__table-chat-group">
                <button class="main__btn">Сообщение</button>
                <button class="main__btn">Рассылка</button>
            </div>
            <div class="main__table-chat-group">
                <div class="main__subheader-filing main__subheader-filing-chat">
                    <select name="filing-date">
                        <option value="" disabled selected>Выбрать пользователя</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="main__subheader-filing main__subheader-filing-chat">
                    <select name="filing-date">
                        <option value="Всем" disabled selected>Отправить выборочно</option>
                        <option value="Всем">Всем</option>
                        <option value="Выборочно">Выборочно</option>
                    </select>
                </div>
            </div>
            <div class="main__table-chat-group">
                <form>
                    <textarea id="subheader__input-item" placeholder="Написать"></textarea>
                </form>
            </div>
            <div class="main__table-chat-group">
                <button class="main__btn-green">Отправить</button>
                <button class="main__btn">Отменить</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush