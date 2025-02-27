@extends('disp.layout')
@section('title', 'Водители - taxi.wazir.kg')
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

/* Пример для стилей статусов */
.status-free {
    color: green;
    font-weight: bold;
}

.status-busy {
    color: red;
    font-weight: bold;
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
    <div class="main__subheader-drivers-info">
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
<div class="main__drivers-table">
    <div class="main__table main__paybalance-table">
        <table>
            <thead>
                <tr>
                    <th>Статусы</th>
                    <th>Состояние</th>
                    <th>Позывной</th>
                    <th>Ф.И.О</th>
                    <th>Телефон</th>
                    <th>Условия работы</th>
                    <th>Баланс</th>
                    <th>Лимит</th>
                    <th>ВУ</th>
                </tr>
            </thead>
            <tbody id="drivers-table-body">
            </tbody>
        </table>
        <div class="main__table-footer">
            <div class="main__table-driver">
                <button>Водители: {{ $drivers->count() }}</button>
                <button>Баланс: 15 000</button>
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
                <div class="main__table-pagination-item">
                    <button>2</button>
                </div>
                <div class="main__table-pagination-item">
                    <button>3</button>
                </div>
                <div class="main__table-pagination-next">
                    <button>
                        <img src="{{ asset('assets/img/disp/ico/next.png') }}" alt="next">
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
function updateDrivers() {
    $.ajax({
        url: "{{ route('dispatcher.backend.drivers.list') }}",
        method: "GET",
        success: function(data) {
            let tbody = $('#drivers-table-body');
            tbody.empty();

            data.forEach(function(driver) {
                // Форматируем телефон
                let rawPhone = (driver.phone || '').replace(/\D/g, '');
                let formattedPhone = driver.phone || '';
                if (rawPhone.length === 9) {
                    formattedPhone = '+996 ' + rawPhone.substr(0, 3) + ' ' +
                        rawPhone.substr(3, 2) + '-' +
                        rawPhone.substr(5, 2) + '-' +
                        rawPhone.substr(7, 2);
                }

                // Статус
                let statusHtml = driver.is_confirmed ?
                    '<span class="status-free">Подтвержден</span>' :
                    '<span class="status-busy">Не подтвержден</span>';

                // Водительское удостоверение (номер)
                let licenseHtml = driver.license_number ?
                    driver.license_number :
                    'Не указано';

                // Формируем строку таблицы
                let row = `
                    <tr>
                        <td>Новый пользователь</td>
                        <td>${statusHtml}</td>
                        <td>${driver.id}</td>
                        <td>${driver.full_name ?? ''}</td>
                        <td>${formattedPhone}</td>
                        <td>Основной</td>
                        <td>0</td>
                        <td>0</td>
                        <td>${licenseHtml}</td>
                    </tr>
                `;
                tbody.append(row);
            });
        },
        error: function(xhr) {
            console.error("Ошибка при получении списка водителей:", xhr.responseText);
        }
    });
}

$(document).ready(function() {
    updateDrivers();
});
</script>

@endpush