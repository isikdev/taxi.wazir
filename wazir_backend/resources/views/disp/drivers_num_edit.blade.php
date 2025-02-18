{{-- resources/views/disp/drivers_num_edit.blade.php --}}

@extends('disp.layout')
@section('title', 'Создание водителя - taxi.wazir.kg')
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

.invalid {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
}

.main__subheader-filing select {
    background-color: #2f2f2f;
    color: #fff;
    border: 1px solid #444;
    border-radius: 4px;
    padding: 5px 10px;
    width: 180px;
    appearance: none;
}

.personal-data-form .form-column .form-group input[type="text"] {
    width: auto;
    text-align: center;
}
</style>
@endpush

@section('content')
<div class="main__subheader-drivers">
    <div class="main__subheader-add">
        <div class="main__subheader-filing">
            <button class="main__btn">
                <a href="{{ route('dispatcher.backend.drivers_control_edit') }}">Водитель</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn main__btn-driver">
                <a href="{{ route('dispatcher.backend.drivers_num_edit', ['driver' => $driver->id]) }}">Автомобиль</a>
            </button>
        </div>
        <div class="main__subheader-filing">
            <button class="main__btn">
                @if($driver->car_brand && $driver->car_model && $driver->car_color && $driver->car_year &&
                $driver->service_type && $driver->category && $driver->tariff && $driver->license_plate)
                <a href="{{ route('dispatcher.backend.drivers_car_edit', ['driver' => $driver->id]) }}">Фото
                    автомобиля</a>
                @else
                <span class="disabled-link">Фото автомобиля</span>
                @endif
            </button>
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

<div class="main__driversedit-wrapper">
    <h3 class="title">Автомобиль</h3>

    {{-- Основная форма --}}
    <form class="personal-data-form-wrapper"
        action="{{ route('dispatcher.backend.process_drivers_num_edit', ['driver' => $driver->id]) }}" method="POST">
        @csrf
        <div class="personal-data-form">
            <div class="form-column">
                <div class="form-group">
                    <label for="car_brand">Марка</label>
                    <div class="main__subheader-filing">
                        <select id="car_brand" name="car_brand">
                            <option value="" disabled selected>Выберите марку</option>
                            @if (!empty($carSelects['brands']))
                            @foreach ($carSelects['brands'] as $brand)
                            <option value="{{ $brand }}"
                                {{ old('car_brand', $driver->car_brand ?? '') == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_brand')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Модель -->
                <div class="form-group">
                    <label for="car_model">Модель</label>
                    <div class="main__subheader-filing">
                        <select id="car_model" name="car_model">
                            <option value="" disabled selected>Выберите модель</option>
                            @if (!empty($carSelects['models']))
                            @foreach ($carSelects['models'] as $model)
                            <option value="{{ $model }}"
                                {{ old('car_model', $driver->car_model) == $model ? 'selected' : '' }}>
                                {{ $model }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_model')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Цвет -->
                <div class="form-group">
                    <label for="car_color">Цвет</label>
                    <div class="main__subheader-filing">
                        <select id="car_color" name="car_color">
                            <option value="" disabled selected>Выберите цвет</option>
                            @if (!empty($carSelects['colors']))
                            @foreach ($carSelects['colors'] as $color)
                            <option value="{{ $color }}"
                                {{ old('car_color', $driver->car_color) == $color ? 'selected' : '' }}>
                                {{ $color }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_color')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Год -->
                <div class="form-group">
                    <label for="car_year">Год</label>
                    <div class="main__subheader-filing">
                        <select id="car_year" name="car_year">
                            <option value="" disabled selected>Выберите год</option>
                            @if (!empty($carSelects['years']))
                            @foreach ($carSelects['years'] as $year)
                            <option value="{{ $year }}"
                                {{ old('car_year', $driver->car_year) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_year')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <h3 class="title">Комплектация и брендинг</h3>

                <!-- КПП (transmission) -->
                <div class="form-group">
                    <label for="transmission">КПП</label>
                    <div class="main__subheader-filing">
                        <select id="transmission" name="transmission">
                            <option value="" disabled selected>Выберите КПП</option>
                            @if (!empty($carSelects['transmissions']))
                            @foreach ($carSelects['transmissions'] as $trans)
                            <option value="{{ $trans }}"
                                {{ old('transmission', $driver->transmission) == $trans ? 'selected' : '' }}>
                                {{ $trans }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Бустеры (boosters) -->
                <div class="form-group">
                    <label for="boosters">Бустеры</label>
                    <div class="main__subheader-filing">
                        <select id="boosters" name="boosters">
                            <option value="" disabled selected>Кол-во бустеров</option>
                            @if (!empty($carSelects['boosters']))
                            @foreach ($carSelects['boosters'] as $booster)
                            <option value="{{ $booster }}"
                                {{ old('boosters', $driver->boosters) == $booster ? 'selected' : '' }}>
                                {{ $booster }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Детское кресло (child_seat) -->
                <div class="form-group">
                    <label for="child_seat">Детское кресло</label>
                    <div class="main__subheader-filing">
                        <select id="child_seat" name="child_seat">
                            <option value="" disabled selected>Выберите детское кресло</option>
                            @if (!empty($carSelects['child_seats']))
                            @foreach ($carSelects['child_seats'] as $cs)
                            <option value="{{ $cs }}"
                                {{ old('child_seat', $driver->child_seat) == $cs ? 'selected' : '' }}>
                                {{ $cs }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <h3 class="title">Параметры</h3>

                <!-- Парковая машина (Да/Нет) -->
                <div class="form-group">
                    <label for="parking_car">Парковая машина</label>
                    <div class="main__subheader-filing">
                        <select id="parking_car" name="parking_car">
                            <option value="" disabled selected>Выберите вариант</option>
                            @if (!empty($carSelects['parking_car_options']))
                            @foreach ($carSelects['parking_car_options'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('parking_car', $driver->parking_car) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="callsign">Позывной</label>
                    <input type="text" id="callsign" name="callsign" placeholder="Введите позывной"
                        value="{{ old('callsign', $driver->callsign) }}">
                    @error('callsign')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-column">
                <!-- Гос.номер -->
                <div class="form-group">
                    <label for="license_plate">Гос.номер</label>
                    <input type="text" id="license_plate" name="license_plate" placeholder="01 123 ABC"
                        value="{{ old('license_plate', $driver->license_plate) }}">
                    @error('license_plate')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- VIN -->
                <div class="form-group">
                    <label for="vin">VIN</label>
                    <input type="text" id="vin" name="vin" placeholder="17 символов"
                        value="{{ old('vin', $driver->vin) }}">
                    @error('vin')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Номер кузова -->
                <div class="form-group">
                    <label for="body_number">Номер кузова</label>
                    <input type="text" id="body_number" name="body_number" placeholder="12 символов"
                        value="{{ old('body_number', $driver->body_number) }}">
                    @error('body_number')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <!-- СТС -->
                <div class="form-group">
                    <label for="sts">СТС</label>
                    <input type="text" id="sts" name="sts" placeholder="10 цифр" value="{{ old('sts', $driver->sts) }}">
                    @error('sts')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="has_nakleyka">Наклейка</label>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="has_nakleyka" name="has_nakleyka" value="1"
                            {{ old('has_nakleyka', $driver->has_nakleyka) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="has_lightbox">Лайтбокс - Шашка</label>
                    <div class="custom-checkbox">
                        <input type="checkbox" id="has_lightbox" name="has_lightbox" value="1"
                            {{ old('has_lightbox', $driver->has_lightbox) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                    </div>
                </div>

                <h3 class="title">Услуги</h3>
                <div class="form-group">
                    <label for="service_type">Услуга</label>
                    <div class="main__subheader-filing">
                        <select id="service_type" name="service_type">
                            <option value="" disabled selected>Выберите услугу</option>
                            @if (!empty($carSelects['services']))
                            @foreach ($carSelects['services'] as $service)
                            <option value="{{ $service }}"
                                {{ old('service_type', $driver->service_type) == $service ? 'selected' : '' }}>
                                {{ $service }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_brand')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category">Категория</label>
                    <div class="main__subheader-filing">
                        <select id="category" name="category">
                            <option value="" disabled selected>Выберите категорию</option>
                            @if (!empty($carSelects['categories']))
                            @foreach ($carSelects['categories'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('category', $driver->category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_brand')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tariff">Тариф</label>
                    <div class="main__subheader-filing">
                        <select id="tariff" name="tariff">
                            <option value="" disabled selected>Выберите тариф</option>
                            @if (!empty($carSelects['tariffs']))
                            @foreach ($carSelects['tariffs'] as $t)
                            <option value="{{ $t }}" {{ old('tariff', $driver->tariff) == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    @error('car_brand')
                    <p class="invalid">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <button type="submit" class="main__btn-green">Далее</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    $('#license_plate').mask('00 000 AAA', {
        translation: {
            '0': {
                pattern: /[0-9]/
            },
            'A': {
                pattern: /[A-Za-zА-Яа-я]/
            }
        },
        placeholder: "__ ___ ___"
    }).on('input', function() {
        this.value = this.value.toUpperCase();
    });

    $('#vin').mask('AAAAAAAAAAAAAAAAA', {
        translation: {
            'A': {
                pattern: /[A-Z0-9]/
            }
        },
        placeholder: "17 символов"
    }).on('input', function() {
        this.value = this.value.toUpperCase();
    });

    $('#body_number').mask('AAAAAAAAAAAA', {
        translation: {
            'A': {
                pattern: /[A-Z0-9]/
            }
        },
        placeholder: "12 символов"
    }).on('input', function() {
        this.value = this.value.toUpperCase();
    });

    $('#sts').mask('0000000000', {
        placeholder: "XXXXXXXXXX"
    });
});
</script>
@endpush