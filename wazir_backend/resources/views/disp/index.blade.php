@extends('disp.layout')
@section('title', 'Главная Диспетчерская - taxi.wazir.kg')
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
</style>
@endpush
@section('content')
<div class="main__subheader-drivers">
    <div class="main__subheader-filter">
        <a href="#"><img src="{{ asset('assets/img/disp/ico/burger.png') }}" alt="burger"></a>
        <a href="#"><img src="{{ asset('assets/img/disp/ico/chart.png') }}" alt="chart"></a>
        <a href="#"><img src="{{ asset('assets/img/disp/ico/earth.png') }}" alt="earth"></a>
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
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
    </div>
</div>
<div class="main__drivers-table">
    <div class="main__table main__paybalance-table">
        <table class="main__table">
            <thead>
                <tr>
                    <th>Статус</th>
                    <th>Подтверждение</th>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Тип</th>
                    <th>Заказы</th>
                    <th>Отмены</th>
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
            {{ $drivers->links() }}
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/disp/script.js') }}"></script>
<script>
function updateDrivers() {
    $.ajax({
        url: "{{ route('dispatcher.backend.drivers.list') }}",
        method: "GET",
        success: function(response) {
            let tbody = $('#drivers-table-body');
            tbody.empty();

            let drivers = Array.isArray(response) ? response : (response.data || []);

            if (drivers && drivers.length > 0) {
                drivers.forEach(function(driver) {
                    let rawPhone = driver.phone ? driver.phone.replace(/\D/g, '') : '';
                    let formattedPhone = driver.phone || '';

                    if (rawPhone.length === 9) {
                        formattedPhone = '+996 ' + rawPhone.substr(0, 3) + ' ' +
                            rawPhone.substr(3, 2) + '-' +
                            rawPhone.substr(5, 2) + '-' +
                            rawPhone.substr(7, 2);
                    }

                    let row = '<tr>' +
                        '<td>' + (driver.is_confirmed ? 'Подтвержден' : 'Новый пользователь') +
                        '</td>' +
                        '<td><span class="' + (driver.is_confirmed ? 'status-free' :
                            'status-busy') + '">' +
                        (driver.is_confirmed ? 'Подтвержден' : 'Не подтвержден') + '</span></td>' +
                        '<td>' + (driver.id || '') + '</td>' +
                        '<td>' + (driver.full_name || '') + '</td>' +
                        '<td>' + formattedPhone + '</td>' +
                        '<td>' + (driver.service_type || 'Основной') + '</td>' +
                        '<td>0</td>' +
                        '<td>0</td>' +
                        '<td>' + (driver.license_number || 'Не указано') + '</td>' +
                        '</tr>';

                    tbody.append(row);
                });
            }

            // Обновляем счетчик водителей
            $('.main__table-driver button:first-child').text('Водители: ' + (drivers ? drivers.length : 0));
        },
        error: function(xhr, status, error) {
            console.error('Ошибка:', error);
            console.log('Статус:', status);
            console.log('Ответ сервера:', xhr.responseText);
        }
    });
}

// Обновляем данные каждые 30 секунд
setInterval(updateDrivers, 30000);

// Первое обновление при загрузке страницы
$(document).ready(function() {
    updateDrivers();
});
</script>
@endpush