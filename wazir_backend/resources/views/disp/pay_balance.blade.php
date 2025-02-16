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
</style>
@endpush
@section('content')

<div class="main__subheader-paybalance">
    <div class="main__subheader-add">
        <button>Активные</button>
        <button>Заблокированные</button>
        <button>В ожидании</button>
        <button>Отмененные</button>
    </div>
    <div class="main__subheader-balance">
        <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
        <p>Баланс: 10,000</p>
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
                    <th>Статус</th>
                </tr>
            </thead>

            <tbody>
                @foreach($drivers as $driver)
                <tr>
                    <td>{{ $driver->full_name }}</td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        Не сущ
                        @endif
                    </td>
                    <td>
                        <a href="tel:{{ $driver->phone }}">+996 {{ $driver->phone }}</a>
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        Не сущ
                        @endif
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        Не сущ
                        @endif
                    </td>
                    <td>
                        @if(!$driver->is_confirmed)
                        <span class="status-busy">Новый пользователь</span>
                        @else
                        Не сущ
                        @endif
                    </td>
                    <td>Не сущ</td>

                    <td class="small-col 
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        @if($driver->is_confirmed)
                        <form class="main__paybalance-table-td">
                            <img src="{{ asset('assets/img/disp/ico/balance.png') }}" alt="balance">
                            <input type="text" placeholder="150">
                        </form>
                        @else
                        <span style="color: #aaa;">—</span>
                        @endif
                    </td>

                    <td class="small-col 
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        @if($driver->is_confirmed)
                        <button class="main__btn-short">Подтвердить</button>
                        @else
                        <button class="main__btn-short" style="pointer-events:none; opacity:0.5;">
                            Подтвердить
                        </button>
                        @endif
                    </td>

                    <td class="small-col 
            @if(!$driver->is_confirmed) disabled-cell @endif">
                        В ожидании подтверждения
                    </td>

                    <td class=" 
                    @if(!$driver->is_confirmed) disabled-cell @endif">
                        <form>
                            <div class="custom-checkbox">
                                <input type="checkbox" class="custom-input">
                                <span class="checkmark"></span>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
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
@endpush