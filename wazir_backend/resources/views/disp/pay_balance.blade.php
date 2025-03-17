@extends('disp.layout')
@section('title', 'Пополнение баланса - taxi.wazir.kg')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/scss/main.css') }}">
<style>
.main__subheader {
    display: none;
}

.main__subheader-paybalance {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.main__subheader-add button {
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.main__subheader-add button.active {
    background-color: #3498db;
    color: white;
    border-color: #2980b9;
}

.small-col {
    max-width: 150px;
    text-align: center;
    overflow: hidden;
}

.disabled-cell {
    pointer-events: none;
    opacity: 0.5;
}

.main__table tbody td {
    border-bottom: 1px solid #444;
}

.alert {
    padding: 10px 15px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>
@endpush
@section('content')

<div id="messageContainer" style="display: none;" class="alert"></div>

<div class="main__subheader-paybalance">
    <div class="main__subheader-add">
        <button>Активные</button>
        <button>Заблокированные</button>
        <button>В ожидании</button>
        <button>Отмененные</button>
    </div>
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: {{ number_format($totalBalance ?? 0, 0, '.', ',') }}</p>
    </div>
</div>
<div class="main__paybalance">
    <div class="main__table main__paybalance-table">
        <table>
            <thead>
                <tr>
                    <th>Ф.И.О</th>
                    <th>Позывной</th>
                    <th>Телефон</th>
                    <th>Автомобиль</th>
                    <th>Марка</th>
                    <th>Тариф</th>
                    <th>Баланс</th>
                    <th class="small-col">Пополнение</th>
                    <th class="small-col">Подтвердить</th>
                    <th class="small-col">Статус</th>
                </tr>
            </thead>

            <tbody>
                @foreach($drivers as $driver)
                <tr data-driver-id="{{ $driver->id }}">
                    <td>{{ $driver->full_name }}</td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        {{ $driver->call_sign ?? ($driver->callsign ?? 'Не назначен') }}
                        @endif
                    </td>
                    <td>
                        <a href="tel:{{ $driver->phone }}">{{ $driver->phone }}</a>
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @elseif($driver->car && is_object($driver->car))
                        {{ $driver->car->brand ?? 'Не указана' }}
                        @elseif(isset($driver->car_brand))
                        {{ $driver->car_brand }}
                        @elseif(isset($driver->brand))
                        {{ $driver->brand }}
                        @elseif(isset($driver->auto) && is_object($driver->auto))
                        {{ $driver->auto->brand ?? 'Не указана' }}
                        @else
                        Не указана
                        @endif
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @elseif($driver->car && is_object($driver->car))
                        {{ $driver->car->model ?? 'Не указан' }}
                        @elseif(isset($driver->car_model))
                        {{ $driver->car_model }}
                        @elseif(isset($driver->model))
                        {{ $driver->model }}
                        @elseif(isset($driver->auto) && is_object($driver->auto))
                        {{ $driver->auto->model ?? 'Не указан' }}
                        @else
                        Не указан
                        @endif
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        @if(is_object($driver->tariff))
                        {{ $driver->tariff->name }}
                        @elseif(is_string($driver->tariff))
                        {{ $driver->tariff }}
                        @else
                        Не указан
                        @endif
                        @endif
                    </td>
                    <td class="driver-balance">{{ $driver->balance ?? 0 }}</td>

                    <td class="small-col 
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        @if($driver->is_confirmed)
                        <form class="main__paybalance-table-td balance-form">
                            <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
                            <input type="text" class="balance-amount" placeholder="150">
                            <input type="hidden" name="payment_method" value="cash" class="payment-method">
                        </form>
                        @else
                        <span style="color: #aaa;">—</span>
                        @endif
                    </td>

                    <td class="small-col 
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        @if($driver->is_confirmed)
                        <button class="main__btn-short confirm-payment-btn">Подтвердить</button>
                        @else
                        <button class="main__btn-short" style="pointer-events:none; opacity:0.5;">
                            Подтвердить
                        </button>
                        @endif
                    </td>

                    <td class="small-col payment-status
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        В ожидании подтверждения
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
        <div class="main__table-footer">
            <div class="main__table-driver">
                <button>Водители: 0</button>
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
</div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
$(document).ready(function() {
    // CSRF токен для запросов
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Обработка нажатия на кнопку "Подтвердить"
    $('.confirm-payment-btn').click(function() {
        const row = $(this).closest('tr');
        const driverId = row.data('driver-id');
        const amountInput = row.find('.balance-amount');
        const amount = amountInput.val();
        const statusCell = row.find('.payment-status');
        const balanceCell = row.find('.driver-balance');

        // Проверка на пустое поле
        if (!amount || isNaN(amount) || parseFloat(amount) <= 0) {
            showMessage('Пожалуйста, введите корректную сумму пополнения', 'danger');
            return;
        }

        // Отправка запроса на пополнение баланса
        $.ajax({
            url: '{{ route("dispatcher.backend.process_balance_payment") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                driver_id: driverId,
                amount: amount
            },
            beforeSend: function() {
                statusCell.text('Обработка...');
            },
            success: function(response) {
                if (response.success) {
                    statusCell.text('Успешно выполнено');
                    balanceCell.text(response.new_balance);
                    amountInput.val('');
                    showMessage('Баланс успешно пополнен', 'success');

                    // Обновляем общий баланс в шапке
                    if (response.total_balance) {
                        $('.main__subheader-balance p').text('Баланс: ' +
                            new Intl.NumberFormat('ru-RU').format(response
                                .total_balance));
                    }

                    // Сброс статуса через 3 секунды
                    setTimeout(function() {
                        statusCell.text('В ожидании подтверждения');
                    }, 3000);
                } else {
                    statusCell.text('Ошибка');
                    showMessage(response.message ||
                        'Произошла ошибка при пополнении баланса', 'danger');

                    // Сброс статуса через 5 секунд
                    setTimeout(function() {
                        statusCell.text('В ожидании подтверждения');
                    }, 5000);
                }
            },
            error: function(xhr) {
                statusCell.text('Ошибка');

                let errorMessage = 'Произошла ошибка при пополнении баланса';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showMessage(errorMessage, 'danger');

                // Сброс статуса через 5 секунд
                setTimeout(function() {
                    statusCell.text('В ожидании подтверждения');
                }, 5000);

                // Запрос обновленного баланса через 2 секунды
                setTimeout(function() {
                    $.ajax({
                        url: '{{ route("dispatcher.backend.drivers") }}',
                        type: 'GET',
                        success: function() {
                            // Обновляем общий баланс, запрашивая его заново
                            location.reload();
                        }
                    });
                }, 2000);
            }
        });
    });

    /**
     * Отображение сообщения пользователю
     */
    function showMessage(message, type) {
        const container = $('#messageContainer');
        container.text(message)
            .removeClass('alert-success alert-danger')
            .addClass(`alert-${type}`)
            .fadeIn();

        // Авто-скрытие сообщения через 5 секунд
        setTimeout(function() {
            container.fadeOut();
        }, 5000);
    }
});
</script>
@endpush