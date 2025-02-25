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
                <input type="search" id="userSearchInput" placeholder="Поиск">
                <button type="button" id="userSearchButton" style="padding: 0px;">
                    <img src="{{ asset('assets/img/disp/ico/search.png') }}" alt="search">
                </button>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="status-filter" id="statusFilter">
                    <option value="" selected>Статусы (все)</option>
                    <option value="online">Онлайн</option>
                    <option value="busy">Занят</option>
                    <option value="free">Свободен</option>
                    <option value="offline">Офлайн</option>
                </select>
            </form>
        </div>
        <div class="main__subheader-filing">
            <form action="#">
                <select name="state-filter" id="stateFilter">
                    <option value="" selected>Состояние (все)</option>
                    <option value="confirmed">Подтвержден</option>
                    <option value="not-confirmed">Не подтвержден</option>
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
                        <td>
                            @if($user->status == 'online')
                            <span class="status-free">Онлайн</span>
                            @elseif($user->status == 'busy')
                            <span class="status-busy">Занят</span>
                            @elseif($user->status == 'free')
                            <span class="status-free">Свободен</span>
                            @else
                            <span class="status-busy">Офлайн</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_confirmed)
                            <span class="status-free">Подтвержден</span>
                            @else
                            <span class="status-busy">Не подтвержден</span>
                            @endif
                        </td>
                        <td>{{ $user->callsign ?? 'Не указан' }}</td>
                        <td>{{ $user->full_name ?? 'Не указано' }}</td>
                        <td>{{ $user->phone ?? 'Не указан' }}</td>
                        <td>
                            <a href="#" class="sms-button" data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->full_name }}">
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
                    <select name="user_select" id="userSelect">
                        <option value="" disabled selected>Выбрать пользователя</option>
                        <option value="all">Всем</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="main__subheader-filing main__subheader-filing-chat">
                    <select name="send_type" id="sendTypeSelect">
                        <option value="" disabled selected>Отправить выборочно</option>
                        <option value="all">Всем</option>
                        <option value="selective">Выборочно</option>
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
<script>
$(document).ready(function() {
    // Сохраняем все строки таблицы при загрузке страницы
    var allTableRows = $('tbody tr').toArray();

    // Функция фильтрации пользователей
    function filterUsers() {
        var searchText = $('#userSearchInput').val().toLowerCase();
        var statusFilter = $('#statusFilter').val();
        var stateFilter = $('#stateFilter').val();

        // Скрываем все строки
        $('tbody tr').hide();

        // Фильтруем строки таблицы
        $(allTableRows).each(function(index, row) {
            var $row = $(row);
            var name = $row.find('td:eq(3)').text().toLowerCase();
            var phone = $row.find('td:eq(4)').text().toLowerCase();
            var callsign = $row.find('td:eq(2)').text().toLowerCase();

            var statusText = $row.find('td:eq(0) span').text().toLowerCase();
            var stateText = $row.find('td:eq(1) span').text().toLowerCase();

            // Определяем значение статуса для сравнения с фильтром
            var rowStatus = '';
            if (statusText.includes('онлайн')) rowStatus = 'online';
            else if (statusText.includes('занят')) rowStatus = 'busy';
            else if (statusText.includes('свободен')) rowStatus = 'free';
            else rowStatus = 'offline';

            // Определяем значение состояния для сравнения с фильтром
            var rowState = '';
            if (stateText.includes('подтвержден') && !stateText.includes('не')) rowState = 'confirmed';
            else rowState = 'not-confirmed';

            // Проверяем соответствие всем фильтрам
            var matchesSearch = searchText === '' ||
                name.includes(searchText) ||
                phone.includes(searchText) ||
                callsign.includes(searchText);

            var matchesStatus = statusFilter === '' || rowStatus === statusFilter;
            var matchesState = stateFilter === '' || rowState === stateFilter;

            // Если строка соответствует всем критериям, показываем ее
            if (matchesSearch && matchesStatus && matchesState) {
                $row.show();
            }
        });

        // Обновляем счетчики в информационной панели
        updateCounters();
    }

    // Функция для обновления счетчиков водителей
    function updateCounters() {
        var visibleRows = $('tbody tr:visible').length;
        var freeDrivers = $('tbody tr:visible td:first-child .status-free').length;
        var busyDrivers = visibleRows - freeDrivers;

        $('.main__header-tags li:first-child').text('На линии ' + visibleRows + ' водителей');
        $('.main__header-tags li:nth-child(2)').html('<span class="status-span free"></span> ' + freeDrivers +
            ' свободный');
        $('.main__header-tags li:last-child').html('<span class="status-span busy"></span> ' + busyDrivers +
            ' занят');
    }

    // Обработчики событий для фильтров и поиска
    $('#userSearchInput').on('keyup', filterUsers);
    $('#userSearchButton').on('click', filterUsers);
    $('#statusFilter, #stateFilter').on('change', filterUsers);

    // Обработчик клика по кнопке СМС
    $('.sms-button').on('click', function(e) {
        e.preventDefault(); // Предотвращаем стандартное поведение ссылки

        var userId = $(this).data('user-id');

        // Устанавливаем выбранного пользователя в селекторе
        $('#userSelect').val(userId);

        // Устанавливаем тип отправки "Выборочно"
        $('#sendTypeSelect').val('selective');

        // Прокручиваем к форме отправки сообщения
        $('html, body').animate({
            scrollTop: $('.main__table-chat-group-wrapper').offset().top - 20
        }, 500);

        // Устанавливаем фокус на текстовое поле
        $('#subheader__input-item').focus();
    });

    // Обработчик изменения значения в поле выбора пользователя
    $('#userSelect').on('change', function() {
        var selectedValue = $(this).val();

        // Если выбрано значение "Всем", устанавливаем соответствующее значение во втором поле
        if (selectedValue === 'all') {
            $('#sendTypeSelect').val('all');
        }
        // Если выбран конкретный пользователь, устанавливаем "Выборочно" во втором поле
        else if (selectedValue !== '') {
            $('#sendTypeSelect').val('selective');
        }
    });

    // Обработчик изменения значения в поле типа отправки
    $('#sendTypeSelect').on('change', function() {
        var selectedValue = $(this).val();

        // Если выбрано значение "Всем", устанавливаем соответствующее значение в первом поле
        if (selectedValue === 'all') {
            $('#userSelect').val('all');
        }
        // Если выбрано "Выборочно", но в первом поле "Всем", сбрасываем первое поле
        else if (selectedValue === 'selective' && $('#userSelect').val() === 'all') {
            $('#userSelect').val('');
        }
    });

    // Инициализация счетчиков при загрузке страницы
    updateCounters();
});
</script>
@endpush