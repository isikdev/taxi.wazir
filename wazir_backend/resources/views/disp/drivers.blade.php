<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Водители - taxi.wazir.kg</title>
    <link rel="stylesheet" href="{{ asset('assets/css/disp/main.css') }}">
</head>

<body>
    <div class="wrapper">
        <div class="navbar__settings navbar__menu">
            <div class="navbar__menu-links">
                <h4>Информация</h4>
                <nav>
                    <ul>
                        <li><a href="#">Документы</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Техподдержка</h4>
                <nav>
                    <ul>
                        <li><a href="#">Техподдержка</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="navbar__menu-info navbar__menu">
            <div class="navbar__menu-links">
                <h4>Диспетчерская</h4>
                <nav>
                    <ul>
                        <li><a href="{{ route('dispatcher.backend.drivers') }}">Статистика</a></li>
                        <li><a href="./get_balance.html">Запрос на баланс</a></li>
                        <li><a href="./new_order.html">Новый заказ</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Автопарк</h4>
                <nav>
                    <ul>
                        <li><a href="./drivers_control_edit.html">Создание водителя</a></li>
                        <li><a href="./pay_balance.html">Пополнение баланса</a></li>
                        <li><a href="./drivers.html">Водители</a></li>
                        <li><a href="./cars.html">Автомобили</a></li>
                        <li><a href="./chat.html">Чат</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links">
                <h4>Контроль</h4>
                <nav>
                    <ul>
                        <li><a href="./drivers_control.html">Фото контроль</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="navbar__menu-settings navbar__menu">
            <div class="navbar__menu-links">
                <h4>Основные</h4>
                <nav>
                    <ul>
                        <li><a href="#">Профиль партнера</a></li>
                        <li><a href="#">Категория транзакций</a></li>
                    </ul>
                </nav>
            </div>
            <div class="navbar__menu-links" style="position: absolute; bottom: 10%;">
                <h4>Интерфейс</h4>
                <div class="main__subheader-filing">
                    <h4>Язык Интерфейса</h4>
                    <form action="#">
                        <select name="filing-date" style="width: 100%;">
                            <option value="Русский">Русский</option>
                            <option value="Киргизский">Киргизский</option>
                        </select>
                    </form>
                </div>
                <div class="main__subheader-filing">
                    <h4>Тема</h4>
                    <form action="#">
                        <select name="filing-date" style="width: 100%;">
                            <option value="Ночная Тема">Ночная Тема</option>
                            <option value="Светлая Тема">Светлая Тема</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="navbar">
            <div class="navbar__content">
                <div class="navbar__logo">
                    <a href="{{ route('dispatcher.backend.drivers') }}">
                        <img src="{{ asset('assets/img/disp/img/logo/logo_small.png') }}" alt="logo">
                    </a>
                </div>
                <div class="navbar__links">
                    <div class="navbar__links-content">
                        <a class="navbar__links-content-active" href="#">
                            <img src="{{ asset('assets/img/disp/img/ico/disp.png') }}" alt="disp">
                        </a>
                        <a href="#">
                            <img src="{{ asset('assets/img/disp/img/ico/maps.png') }}" alt="maps">
                        </a>
                        <a href="./drivers.html">
                            <img src="{{ asset('assets/img/disp/img/ico/user.png') }}" alt="user">
                        </a>
                        <a href="./cars.html">
                            <img src="{{ asset('assets/img/disp/img/ico/car.png') }}" alt="car">
                        </a>
                        <a href="./analytics.html">
                            <img src="{{ asset('assets/img/disp/img/ico/analytics.png') }}" alt="analytics">
                        </a>
                    </div>
                </div>
                <div class="navbar__links">
                    <div class="navbar__links-content">
                        <a href="#" class="support">
                            <img src="{{ asset('assets/img/disp/img/ico/info.png') }}" alt="info">
                        </a>
                        <a href="#" class="settings">
                            <img src="{{ asset('assets/img/disp/img/ico/settings.png') }}" alt="settings">
                        </a>
                        <a href="#" class="menu">
                            <img src="{{ asset('assets/img/disp/img/ico/menu.png') }}" alt="menu">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="main__header">
                <div class="main__header-logo" style="width: 280px;">
                    <h3>Водители</h3>
                </div>
                <div class="main__header-tags">
                    <ul>
                        <li><a href="./drivers.html">Водитель</a></li>
                        <li><a href="./cars.html">Автомобиль</a></li>
                        <li><a href="./drivers_control.html">Фото контроль</a></li>
                        <li>Тариф</li>
                        <li><a href="./chat.html">Чат</a></li>
                    </ul>
                </div>
                <div class="main__header-search">
                    <div class="main__header-search-item">
                        <form action="#">
                            <input type="search" placeholder="Поиск">
                            <button>
                                <img src="{{ asset('assets/img/disp/img/ico/search.png') }}" alt="search">
                            </button>
                        </form>
                    </div>
                    <div class="main__header-search-profile">
                        <div class="main__header-search-profile-item">
                            <a href="#">
                                <img src="{{ asset('assets/img/disp/img/ico/notif.png') }}" alt="notif">
                            </a>
                        </div>
                        <div class="main__header-search-profile-item">
                            <a href="#">
                                <img src="{{ asset('assets/img/disp/img/ico/user.png') }}" alt="profile">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main__subheader main__subheader-drivers">
                <div class="main__subheader-add">
                    <div class="main__header-search-item">
                        <form action="#" style="background-color: #47484c;">
                            <input type="search" placeholder="Поиск">
                            <button style="padding: 0px;">
                                <img src="{{ asset('assets/img/disp/img/ico/search.png') }}" alt="search">
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
                                <option value="Дата подачи" disabled selected>Состояние</option>
                                <option value="Занят">Занят</option>
                                <option value="В ожидании">В ожидании</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="main__subheader-drivers">
                    <div class="main__header-tags main__subheader-drivers-tags">
                        <ul>
                            <li>На линии {{ $onLine }} водителей</li>
                            <li><span class="status-span free"></span> {{ $free }} свободный</li>
                            <li><span class="status-span busy"></span> {{ $busy }} занят</li>
                            <li>Зарегистрированные: {{ $totalDrivers }}</li>
                        </ul>
                    </div>
                    <div class="main__subheader-balance">
                        <img src="{{ asset('assets/img/disp/img/ico/balance.png') }}" alt="balance">
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
                                <th>ID</th>
                                <th>Ф.И.О</th>
                                <th>Телефон</th>
                                <th>Условия работы</th>
                                <th>Баланс</th>
                                <th>Лимит</th>
                                <th>ВУ</th>
                            </tr>
                        </thead>
                        <tbody id="drivers-table-body">
                            @foreach($drivers as $driver)
                            <tr>
                                <td>Новый пользователь</td>
                                <td><span class="status-busy">Не подтвержден</span></td>
                                <td>{{ $driver->id }}</td>
                                <td>{{ $driver->full_name }}</td>
                                <td>+996 {{$driver->phone;}}</td>
                                <td>Основной</td>
                                <td>0</td>
                                <td>0</td>
                                <td>Не указано</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function updateDrivers() {
            $.ajax({
                url: "{{ route('dispatcher.backend.drivers.list') }}",
                method: "GET",
                success: function(data) {
                    let tbody = $('#drivers-table-body');
                    tbody.empty();
                    data.forEach(function(driver) {
                        let rawPhone = driver.phone.replace(/\D/g, '');
                        let formattedPhone = driver.phone;
                        if (rawPhone.length == 9) {
                            formattedPhone = '+996 ' + rawPhone.substr(0, 3) + ' ' + rawPhone.substr(3, 2) + '-' + rawPhone.substr(5, 2) + '-' + rawPhone.substr(7, 2);
                        }
                        let row = `<tr>
                            <td>Новый пользователь</td>
                            <td><span class="status-busy">Не подтвержден</span></td>
                            <td>${driver.id}</td>
                            <td>${driver.full_name}</td>
                            <td>+996 ${formattedPhone}</td>
                            <td>Основной</td>
                            <td>0</td>
                            <td>0</td>
                            <td>Не указано</td>
                        </tr>`;
                        tbody.append(row);
                    });
                },
                error: function(xhr) {
                    console.error("Ошибка при получении списка водителей:", xhr.responseText);
                }
            });
        }
        setInterval(updateDrivers, 10000);
    </script>
</body>
</html>
